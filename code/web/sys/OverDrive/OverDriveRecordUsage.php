<?php /** @noinspection PhpMissingFieldTypeInspection */


class OverDriveRecordUsage extends DataObject {
	public $__table = 'overdrive_record_usage';
	public $id;
	public $instance;
	public $overdriveId;
	public $year;
	public $month;
	public $timesHeld;
	public $timesCheckedOut;

	public function getUniquenessFields(): array {
		return [
			'instance',
			'overdriveId',
			'year',
			'month',
		];
	}

	public function okToExport(array $selectedFilters): bool {
		$okToExport = parent::okToExport($selectedFilters);
		if (in_array($this->instance, $selectedFilters['instances'])) {
			$okToExport = true;
		}
		return $okToExport;
	}
}