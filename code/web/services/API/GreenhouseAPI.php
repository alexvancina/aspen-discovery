<?php
require_once ROOT_DIR . '/Action.php';
require_once ROOT_DIR . '/sys/Greenhouse/AspenSite.php';

class GreenhouseAPI extends Action
{
	function launch()
	{
		//Make sure the user can access the API based on the IP address
		if (!IPAddress::allowAPIAccessForClientIP()){
			$this->forbidAPIAccess();
		}

		header('Content-type: application/json');
		//header('Content-type: text/html');
		header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

		$method = (isset($_GET['method']) && !is_array($_GET['method'])) ? $_GET['method'] : '';
		if ($method != 'getCatalogConnection' && $method != 'getUserForApiCall' && method_exists($this, $method)) {
			$result = $this->$method();
			$output = json_encode($result);
			require_once ROOT_DIR . '/sys/SystemLogging/APIUsage.php';
			APIUsage::incrementStat('GreenhouseAPI', $method);
		} else {
			$output = json_encode(array('error' => 'invalid_method'));
		}
		echo $output;
	}

	public function getLibraries() : array
	{
		$return = [
			'success' => true,
			'libraries' => [],
		];
		// prep user location
		if (isset($_GET['latitude'])) {
			$userLatitude = $_GET['latitude'];
		} else {
			$userLatitude = 0;
		}
		if (isset($_GET['longitude'])) {
			$userLongitude = $_GET['longitude'];
		} else {
			$userLongitude = 0;
		}
		$sites = new AspenSite();
		$sites->find();
		while($sites->fetch()) {
			if(($sites->appAccess == 1) || ($sites->appAccess == 3)) {
				$fetchLibraryUrl = $sites->baseUrl.'API/GreenhouseAPI?method=getLibrary';
				if($data = file_get_contents($fetchLibraryUrl)) {
					$searchData = json_decode($data);
					foreach ($searchData->library as $findLibrary) {

						$libraryLatitude = $findLibrary->latitude;
						$libraryLongitude = $findLibrary->longitude;
						$libraryUnit = $findLibrary->unit;
						$baseUrl = $findLibrary->baseUrl;

						if($baseUrl == NULL) {
							$baseUrl = $sites->baseUrl;
						}

						if ($userLatitude == 0 && $userLongitude == 0) {
							$return['libraries'][] = [
								'name' => $findLibrary->locationName,
								'librarySystem' => $sites->name,
								'libraryId' => $findLibrary->libraryId,
								'baseUrl' => $baseUrl,
								'accessLevel' => $sites->appAccess,
								'solrScope' => $findLibrary->solrScope,
							];
						} else {
							$theta = ($userLongitude - $libraryLongitude);
							$distance = sin(deg2rad($userLatitude)) * sin(deg2rad($libraryLatitude)) + cos(deg2rad($userLatitude)) * cos(deg2rad($libraryLatitude)) * cos(deg2rad($theta));

							$distance = acos($distance);
							$distance = rad2deg($distance);
							$distance = $distance * 60 * 1.1515;
							if ($libraryUnit == "Km") {
								$distance = $distance * 1.609344;
							}
							$distance = round($distance, 2);
							if ($distance <= 60) {
								$return['libraries'][] = [
									'name' => $findLibrary->locationName,
									'librarySystem' => $sites->name,
									'libraryId' => $findLibrary->libraryId,
									'locationId' => $findLibrary->locationId,
									'baseUrl' => $baseUrl,
									'accessLevel' => $sites->appAccess,
									'distance' => $distance,
									'solrScope' => $findLibrary->solrScope,
								];
							}
						}
					}
				}
			}
		}

		return $return;
	}

	/** @noinspection PhpUnused */
	public function getLibrary() : array {
		$return = [
			'success' => true,
			'library' => [],
		];
		global $configArray;
		require_once ROOT_DIR . '/sys/LibraryLocation/Location.php';
		$location = new Location();
		$location->find();
		while($location->fetch()) {
			if ($location->enableAppAccess == 1){
				$libraryId = $location->libraryId;
				$library = new Library();
				$library->libraryId = $libraryId;
				if ($library->find(true)) {
					$baseUrl = $library->baseUrl;
					if (empty($baseUrl)){
						$baseUrl = $configArray['Site']['url'];
					}

					$solrScope = false;

					$searchLibrary = $library;
					if ($searchLibrary) {
						$solrScope = $searchLibrary->subdomain;
					}

					//TODO: We will eventually want to be able to search individual library branches in the app.
					// i.e. for schools
					//$searchLocation = $location;
					/*if ($searchLocation && $searchLibrary->getNumSearchLocationsForLibrary() > 1) {
						if ($searchLibrary && strtolower($searchLocation->code) == $solrScope) {
							$solrScope .= 'loc';
						} else {
							$solrScope = strtolower($searchLocation->code);
						}
						if (!empty($searchLocation->subLocation)) {
							$solrScope = strtolower($searchLocation->subLocation);
						}
					}*/

					$return['library'][] = [
						'latitude' => $location->latitude,
						'longitude' => $location->longitude,
						'unit' => $location->unit,
						'locationName' => $location->displayName,
						'locationId' => $location->locationId,
						'libraryId' => $libraryId,
						'solrScope' => $solrScope,
						'baseUrl' => $baseUrl,
					];
				}
			}
		}

		return $return;
	}

	/** @noinspection PhpUnused */
	public function addTranslationTerm() : array {
		$translationTerm = new TranslationTerm();
		$translationTerm->term = $_REQUEST['term'];
		if (!$translationTerm->find(true)) {
			$translationTerm->isPublicFacing = $_REQUEST['isPublicFacing'];
			$translationTerm->isAdminFacing = $_REQUEST['isAdminFacing'];
			$translationTerm->isMetadata = $_REQUEST['isMetadata'];
			$translationTerm->isAdminEnteredData = $_REQUEST['isAdminEnteredData'];
			$translationTerm->lastUpdate = time();
			try {
				$translationTerm->insert();
				$result = [
					'success' => true,
					'message' => translate(['text' => 'The term was added.', 'isAdminFacing' => true])
				];
			}catch (Exception $e){
				$result = [
					'success' => false,
					'message' => translate(['text' => 'Could not update term. %1%', 'isAdminFacing'=> true, 1=>(string)$e])
				];
			}
		}else{
			$termChanged = false;
			if ($_REQUEST['isPublicFacing'] && !$translationTerm->isPublicFacing) {
				$translationTerm->isPublicFacing = $_REQUEST['isPublicFacing'];
				$termChanged = true;
			}
			if ($_REQUEST['isAdminFacing'] && !$translationTerm->isAdminFacing) {
				$translationTerm->isAdminFacing = $_REQUEST['isAdminFacing'];
				$termChanged = true;
			}
			if ($_REQUEST['isAdminFacing'] && !$translationTerm->isMetadata) {
				$translationTerm->isMetadata = $_REQUEST['isAdminFacing'];
				$termChanged = true;
			}
			if ($_REQUEST['isAdminEnteredData'] && !$translationTerm->isAdminEnteredData) {
				$translationTerm->isAdminEnteredData = $_REQUEST['isAdminEnteredData'];
				$termChanged = true;
			}
			if ($termChanged) {
				$translationTerm->lastUpdate = time();
				$translationTerm->update();
				$result = [
					'success' => true,
					'message' => translate(['text' => 'The term was updated.', 'isAdminFacing'=> true])
				];
			}else{
				$result = [
					'success' => true,
					'message' => translate(['text' => 'The term already existed.', 'isAdminFacing'=> true])
				];
			}
		}
		return $result;
	}

	public function getDefaultTranslation() {
		$result = [
			'success' => false
		];
		if (!empty($_REQUEST['term']) && !empty($_REQUEST['languageCode'])) {
			$translationTerm = new TranslationTerm();
			$translationTerm->term = $_REQUEST['term'];
			if ($translationTerm->find(true)) {
				$language = new Language();
				$language->code = $_REQUEST['languageCode'];
				if ($language->find(true)) {
					$translation = new Translation();
					$translation->termId = $translationTerm->id;
					$translation->languageId = $language->id;
					if ($translation->find(true)) {
						$result['success'] = true;
						$result['translation'] = $translation->translation;
					}else{
						$result['message'] = 'No translation found';
					}
				}else{
					$result['message'] = 'Could not find language';
				}
			}else{
				$result['message'] = 'Could not find term';
			}
		}else{
			$result['message'] = 'Term and/or languageCode not provided';
		}
		return $result;
	}

	public function setTranslation() {
		$result = [
			'success' => false
		];
		if (!empty($_REQUEST['term']) && !empty($_REQUEST['languageCode']) && !empty($_REQUEST['translation'])) {
			$translationTerm = new TranslationTerm();
			$translationTerm->term = $_REQUEST['term'];
			if ($translationTerm->find(true)) {
				$language = new Language();
				$language->code = $_REQUEST['languageCode'];
				if ($language->find(true)) {
					$translation = new Translation();
					$translation->termId = $translationTerm->id;
					$translation->languageId = $language->id;
					if ($translation->find(true)) {
						if (!$translation->translated) {
							$translation->translation = $_REQUEST['translation'];
							$translation->translated = 1;
							$translation->update();
							$result['success'] = true;
						}else{
							$result['message'] = 'Term already translated';
						}
					}else{
						$result['message'] = 'No translation found';
					}
				}else{
					$result['message'] = 'Could not find language';
				}
			}else{
				$result['message'] = 'Could not find term';
			}
		}else{
			$result['message'] = 'Term, languageCode, and/or translation  not provided';
		}
		return $result;
	}

	function getBreadcrumbs() : array
	{
		return [];
	}
}