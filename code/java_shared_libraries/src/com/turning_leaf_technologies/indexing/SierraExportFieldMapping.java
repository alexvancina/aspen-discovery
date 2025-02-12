package com.turning_leaf_technologies.indexing;

import com.turning_leaf_technologies.logging.BaseIndexingLogEntry;
import com.turning_leaf_technologies.strings.AspenStringUtils;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;

public class SierraExportFieldMapping {
	private String fixedFieldDestinationField = "998";
	private int fixedFieldDestinationFieldInt = 998;
	private char bcode3DestinationSubfield = 'e';
	private char materialTypeSubfield = 'm';
	private char bibLevelLocationsSubfield = ' ';
	private String callNumberExportFieldTag = "c";
	private char callNumberPrestampExportSubfield = 'k';
	private char callNumberPrestamp2ExportSubfield = 'f';
	private char callNumberExportSubfield = 'a';
	private char callNumberCutterExportSubfield = 'b';
	private char callNumberPoststampExportSubfield;
	private String volumeExportFieldTag = "v";
	private String eContentExportFieldTag;
	private String itemPublicNoteExportSubfield;

	public String getFixedFieldDestinationField() {
		return fixedFieldDestinationField;
	}

	public int getFixedFieldDestinationFieldInt() {
		return fixedFieldDestinationFieldInt;
	}

	private void setFixedFieldDestinationField(String bcode3DestinationField) {
		this.fixedFieldDestinationField = bcode3DestinationField;
		this.fixedFieldDestinationFieldInt = Integer.parseInt(fixedFieldDestinationField);
	}

	public char getBcode3DestinationSubfield() {
		return bcode3DestinationSubfield;
	}

	private void setBcode3DestinationSubfield(char bcode3DestinationSubfield) {
		this.bcode3DestinationSubfield = bcode3DestinationSubfield;
	}

	public String getCallNumberExportFieldTag() {
		return callNumberExportFieldTag;
	}

	private void setCallNumberExportFieldTag(String callNumberExportFieldTag) {
		this.callNumberExportFieldTag = callNumberExportFieldTag;
	}

	public char getCallNumberPrestampExportSubfield() {
		return callNumberPrestampExportSubfield;
	}

	private void setCallNumberPrestampExportSubfield(char callNumberPrestampExportSubfield) {
		this.callNumberPrestampExportSubfield = callNumberPrestampExportSubfield;
	}

	public char getCallNumberPrestamp2ExportSubfield() {
		return callNumberPrestamp2ExportSubfield;
	}

	private void setCallNumberPrestamp2ExportSubfield(char callNumberPrestamp2ExportSubfield) {
		this.callNumberPrestamp2ExportSubfield = callNumberPrestamp2ExportSubfield;
	}

	public char getCallNumberExportSubfield() {
		return callNumberExportSubfield;
	}

	private void setCallNumberExportSubfield(char callNumberExportSubfield) {
		this.callNumberExportSubfield = callNumberExportSubfield;
	}

	public char getCallNumberCutterExportSubfield() {
		return callNumberCutterExportSubfield;
	}

	private void setCallNumberCutterExportSubfield(char callNumberCutterExportSubfield) {
		this.callNumberCutterExportSubfield = callNumberCutterExportSubfield;
	}

	public char getCallNumberPoststampExportSubfield() {
		return callNumberPoststampExportSubfield;
	}

	private void setCallNumberPoststampExportSubfield(char callNumberPoststampExportSubfield) {
		this.callNumberPoststampExportSubfield = callNumberPoststampExportSubfield;
	}

	public String getVolumeExportFieldTag() {
		return volumeExportFieldTag;
	}

	private void setVolumeExportFieldTag(String volumeExportFieldTag) {
		this.volumeExportFieldTag = volumeExportFieldTag;
	}

	public String getEContentExportFieldTag() {
		return eContentExportFieldTag;
	}

	private void setEContentExportFieldTag(String eContentExportFieldTag) {
		this.eContentExportFieldTag = eContentExportFieldTag;
	}

	public static SierraExportFieldMapping loadSierraFieldMappings(Connection dbConn, long profileId, BaseIndexingLogEntry logEntry) {
		//Get the Indexing Profile from the database
		SierraExportFieldMapping sierraFieldMapping = new SierraExportFieldMapping();
		try {
			PreparedStatement getSierraFieldMappingsStmt = dbConn.prepareStatement("SELECT * FROM sierra_export_field_mapping where indexingProfileId =" + profileId);
			ResultSet getSierraFieldMappingsRS = getSierraFieldMappingsStmt.executeQuery();
			if (getSierraFieldMappingsRS.next()) {
				sierraFieldMapping.setFixedFieldDestinationField(getSierraFieldMappingsRS.getString("fixedFieldDestinationField"));
				sierraFieldMapping.setBcode3DestinationSubfield(AspenStringUtils.convertStringToChar(getSierraFieldMappingsRS.getString("bcode3DestinationSubfield")));
				sierraFieldMapping.setMaterialTypeSubfield(AspenStringUtils.convertStringToChar(getSierraFieldMappingsRS.getString("materialTypeSubfield")));
				sierraFieldMapping.setBibLevelLocationsSubfield(AspenStringUtils.convertStringToChar(getSierraFieldMappingsRS.getString("bibLevelLocationsSubfield")));
				sierraFieldMapping.setCallNumberExportFieldTag(getSierraFieldMappingsRS.getString("callNumberExportFieldTag"));
				sierraFieldMapping.setCallNumberPrestampExportSubfield(AspenStringUtils.convertStringToChar(getSierraFieldMappingsRS.getString("callNumberPrestampExportSubfield")));
				sierraFieldMapping.setCallNumberPrestamp2ExportSubfield(AspenStringUtils.convertStringToChar(getSierraFieldMappingsRS.getString("callNumberPrestamp2ExportSubfield")));
				sierraFieldMapping.setCallNumberExportSubfield(AspenStringUtils.convertStringToChar(getSierraFieldMappingsRS.getString("callNumberExportSubfield")));
				sierraFieldMapping.setCallNumberCutterExportSubfield(AspenStringUtils.convertStringToChar(getSierraFieldMappingsRS.getString("callNumberCutterExportSubfield")));
				sierraFieldMapping.setCallNumberPoststampExportSubfield(AspenStringUtils.convertStringToChar(getSierraFieldMappingsRS.getString("callNumberPoststampExportSubfield")));
				sierraFieldMapping.setVolumeExportFieldTag(getSierraFieldMappingsRS.getString("volumeExportFieldTag"));
				sierraFieldMapping.setEContentExportFieldTag(getSierraFieldMappingsRS.getString("eContentExportFieldTag"));
				sierraFieldMapping.setItemPublicNoteExportSubfield(getSierraFieldMappingsRS.getString("itemPublicNoteExportSubfield"));

				getSierraFieldMappingsRS.close();
			}
			getSierraFieldMappingsStmt.close();

		} catch (Exception e) {
			logEntry.incErrors("Error reading sierra field mappings", e);
		}
		return sierraFieldMapping;
	}

	private void setItemPublicNoteExportSubfield(String itemPublicNoteExportSubfield) {
		this.itemPublicNoteExportSubfield = itemPublicNoteExportSubfield;
	}

	public String getItemPublicNoteExportSubfield(){
		return itemPublicNoteExportSubfield;
	}

	public char getMaterialTypeSubfield() {
		return materialTypeSubfield;
	}

	public void setMaterialTypeSubfield(char materialTypeSubfield) {
		this.materialTypeSubfield = materialTypeSubfield;
	}

	public char getBibLevelLocationsSubfield() {
		return bibLevelLocationsSubfield;
	}

	public void setBibLevelLocationsSubfield(char bibLevelLocationsSubfield) {
		this.bibLevelLocationsSubfield = bibLevelLocationsSubfield;
	}
}
