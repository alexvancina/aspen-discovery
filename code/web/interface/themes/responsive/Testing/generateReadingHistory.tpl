{strip}
	<div class="row">
		<div class="col-xs-12">
			<h1 id="pageTitle">{$pageTitleShort}</h1>
		</div>
	</div>
	{if !empty($updateMessage)}
		<div class="alert {if !empty($updateMessageIsError)}alert-danger{else}alert-info{/if}">
			{$updateMessage}
		</div>
	{/if}
	<div class="row">
		<div class="col-xs-12">
			<div class="alert alert-info">{translate text="This tool can be used to create sample reading history data for a patron for use during testing. The tool may take several minutes to generate the reading history." isAdminFacing=true}</div>
		</div>
	</div>
	<form id="generateReadingHistoryForm" method="get" role="form">
		<div class='editor'>
			{/strip}
			<div class="form-group">
				<label for="generationType" class="control-label">{translate text='Generate Reading History For' isPublicFacing=true}</label>
				<select id="generationType" name="generationType" class="form-control" onchange="{literal}if ($('#generationType option:selected').val() === '3') {$('#patronBarcodeRow').show();}else{$('#patronBarcodeRow').hide();}{/literal}">
					<option value="1" selected>Test Users with no Reading History</option>
					<option value="2">All Test Users</option>
					<option value="3">Specified Patron</option>
				</select>
			</div>
			{strip}
			<div class="form-group" id="patronBarcodeRow" style="display: none">
				<label for="patronBarcode" class="control-label">{translate text='Patron Barcode (must have logged into Aspen previously)' isPublicFacing=true}</label>
				<input type="text" id="patronBarcode" name="patronBarcode" class="form-control">
			</div>
			<div class="form-group">
				<label for="numberOfYears" class="control-label">{translate text='Number of Years to Generate' isPublicFacing=true}</label>
				<select id="numberOfYears" name="numberOfYears" class="form-control">
					<option value="1" selected="selected">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
				</select>
			</div>
			<div class="form-group">
				<label for="minEntriesPerMonth" class="control-label">{translate text='Min Entries Per Month' isPublicFacing=true}</label>
				<input type="number" id="minEntriesPerMonth" name="minEntriesPerMonth" class="form-control" value="0" min="0" max="30">
			</div>
			<div class="form-group">
				<label for="maxEntriesPerMonth" class="control-label">{translate text='Max Entries Per Month' isPublicFacing=true}</label>
				<input type="number" id="maxEntriesPerMonth" name="maxEntriesPerMonth" class="form-control" value="10" min="1" max="30">
			</div>
			<div class="form-group">
				<div class="checkbox" style="margin: 0">
					<label for='clearExistingReadingHistory'>{translate text="Clear Existing Reading History" isAdminFacing=true}
						<input type="checkbox" name='clearExistingReadingHistory' id='clearExistingReadingHistory'/>
					</label>
				</div>
			</div>
			<div class="form-group">
				<button type="submit" id="generateReadingHistory" name="generateReadingHistory" class="btn btn-primary">{translate text="Generate Reading History" isAdminFacing=true}</button>
			</div>
		</div>
	</form>
{/strip}