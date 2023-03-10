//----------------------------------------------------------
// Copyright (C) Microsoft Corporation. All rights reserved.
// Released under the Microsoft Office Extensible File License
// https://raw.github.com/stephen-hardy/xlsx.js/master/LICENSE.txt
//
// The library includes changes made by GrapeCity.
//
// 1.  Add row height / column width support for exporting.
//     We add the height property in the cells for exporting row height.
//     We add the width property in the cells for exporting column width.
// 2.  Add row/column visible support for exporting.
//     We add the rowVisible property in the first cell of each row to supporting the row visible feature.
//     We add the visible property in the cells for supporting the column visible feature.
// 3.  Add group header support for exporting/importing.
//     We add the groupLevel property in the cells for exporting group.
//     We read the outlineLevel property of the excel row for importing group.
// 4.  Add indent property for nested group for exporting.
//     We add the indent property in the cells of the group row for exporting the indentation for the nested groups.
// 5.  Modify the excel built-in format 'mm-dd-yy' to 'm/d/yyyy'.
// 6.  Add excel built-in format '$#,##0.00_);($#,##0.00)'.
// 7.  Fix issue that couldn't read rich text content of excel cell.
// 8.  Fix issue that couldn't read the excel cell content processed by the string processing function.
// 9.  Fix issue exporting empty sheet 'dimension ref' property incorrect.
// 10. Add frozen rows and columns supporting for exporting/importing.
//     We add frozenPane property that includes rows and columns sub properties in each worksheet.
// 11. Add 'ca' attribute for the cellFormula element for exporting.
// 12. Add formula supporting for importing.
// 13. escapeXML for the formula of the cell.
// 14. Add font color and fill color processing for exporting.
// 15. Add font and fill color processing for importing.
// 16. Add horizontal alignment processing for importing.
// 17. Add column width and row height processing for importing.
// 18. Update merge cells processing for exporting.
// 19. Add merge cells processing for importing.
// 20. Packed cell styles into the style property of cell for exporting.
// 21. Fixed convert excel date value to JS Date object issue.
// 22. Parse the merge cell info to rowSpan and colSpan property of cell.
// 23. Add row collapsed processing for importing.
// 24. Fixed the getting type of cell issue when there is shared formula in the cell.
//
//----------------------------------------------------------

if ((typeof JSZip === 'undefined' || !JSZip) && typeof require === 'function') {
	var JSZip = require('node-zip');
}

function xlsx(file) { 
	'use strict'; // v2.3.2

	var result, zip = new JSZip(), zipTime, processTime, s, content, f, i, j, k, l, t, w, sharedStrings, styles, index, data, val, formula /* GrapeCity: Add formula variable.*/, style, borders, border, borderIndex, fonts, font, fontIndex,
		docProps, xl, xlWorksheets, worksheet, contentTypes = [[], []], props = [], xlRels = [], worksheets = [], id, columns, cols, colWidth, cell, row, merges, merged, rowStr, rowHeightSetting, groupLevelSetting, rowVisible, hiddenColumns, idx, colIndex, groupLevel, frozenPane, frozenRows, frozenCols, fills, fill, fillIndex, /* GrapeCity: Add fill color setting related variables.*/
		numFmts = ['General', '0', '0.00', '#,##0', '#,##0.00', , , '$#,##0.00_);($#,##0.00)' /* GrapeCity: Add built-in accounting format.*/, , '0%', '0.00%', '0.00E+00', '# ?/?', '# ??/??', 'm/d/yyyy' /* GrapeCity: Modify the built-in date format.*/, 'd-mmm-yy', 'd-mmm', 'mmm-yy', 'h:mm AM/PM', 'h:mm:ss AM/PM',
			'h:mm', 'h:mm:ss', 'm/d/yy h:mm', , , , , , , , , , , , , , , '#,##0 ;(#,##0)', '#,##0 ;[Red](#,##0)', '#,##0.00;(#,##0.00)', '#,##0.00;[Red](#,##0.00)', , , , , 'mm:ss', '[h]:mm:ss', 'mmss.0', '##0.0E+0', '@'],
		numFmtArray, fontArray, fillArray, themes, /* GrapeCity: numFmtArray, fontArray, fillArray and themes for importing.*/
		colsSetting, height, /* GrapeCity: Stores the column width and row height for importing.*/
		mergeCellArray, mergeRange, mergeCells, mergeCell, /* GrapeCity: Stores merge cell range for importing.*/
		alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
		defaultFontName = 'Calibri',
		defaultFontSize = 11;

	function numAlpha(i) {
		var t = Math.floor(i / 26) - 1; return (t > -1 ? numAlpha(t) : '') + alphabet.charAt(i % 26); 
	}

	function alphaNum(s) { 
		var t = 0; if (s.length === 2) { t = alphaNum(s.charAt(0)) + 1; } return t * 26 + alphabet.indexOf(s.substr(-1)); 
	}

	function convertDate(input) {
		var d = new Date(1900, 0, 0),
			isDateObject = typeof input === 'object',
			offset = ((isDateObject ? input.getTimezoneOffset() : (new Date()).getTimezoneOffset()) - d.getTimezoneOffset()) * 60000;
		// GrapeCity Begin: Fixed convert excel date value to JS Date object issue.
		return isDateObject ? ((input - d + offset) / 86400000) + 1 : new Date(+d + offset + (input - 1) * 86400000);
		// GrapeCity End
	}

	function typeOf(obj) {
		return ({}).toString.call(obj).match(/\s([a-zA-Z]+)/)[1].toLowerCase();
	}
	
	function getAttr(s, n) { 
		s = s.substr(s.indexOf(n + '="') + n.length + 2); return s.substring(0, s.indexOf('"')); 
	}

	// GrapeCity Begin: Add the function to get the value of child node 
	function getChildNodeValue(s, n) {
		s = s.substr(s.indexOf(n + ' val="') + n.length + 6); return s.substring(0, s.indexOf('"'));
	}
	// GrapeCity End

	// GrapeCity Begin: Add the function to get the color for the font or the fill node. 
	function getColor(s, isFillColor) {
		var colorType, theme, value;
		if ((s.search(/fgcolor/i) === -1 && isFillColor)
			|| (s.search(/color/i) === -1 && !isFillColor)) {
			return undefined;
		}
		s = isFillColor ? s.substring(s.indexOf('<fgColor'), s.indexOf('/>')) : s.substring(s.indexOf('<color'));
		if (s.indexOf('rgb=') !== -1) {
			colorType = 'rgbColor';
			theme = undefined;
			value = getAttr(s, 'rgb');
		} else if (s.indexOf('indexed') !== -1) {
			colorType = 'indexedColor';
			theme = undefined;
			value = getAttr(s, 'indexed');
		} else {
			colorType = 'themeColor';
			theme = getAttr(s, 'theme');
			if (s.indexOf('tint') !== -1) {
				value = getAttr(s, 'tint');
			} else {
				value = undefined;
			}
		}
		return { colorType: colorType, theme: theme, value: value };
	}
	// GrapeCity End
	
	function escapeXML(s) { return typeof s === 'string' ? s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#x27;') : ''; }
	
	function unescapeXML(s) { return typeof s === 'string' ? s.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"').replace(/&#x27;/g, '\'') : ''; }

   if (typeof file === 'string') { // Load
		zipTime = Date.now();
		zip = zip.load(file, { base64: true });
		result = { sheets: [], zipTime: Date.now() - zipTime };
		processTime = Date.now();
		sharedStrings = [];
		styles = [];
		// GrapeCity Begin: initialize the fonts, fills and themes array for importing.
		fonts = [];
		fills = [];
		themes = [];
		// GrapeCity End

		if (s = zip.file('xl/sharedStrings.xml')) { // Process sharedStrings
			// GrapeCity Begin: For fixing issue the content of cell is cut off if it is rich text with multiple style.
			// Do not process i === 0, because s[0] is the text before first t element
			s = s.asText().split(/<si.*?>/g); i = s.length;
			while (--i) {
				content = s[i].split(/<t.*?>/g); j = 1;
				sharedStrings[i - 1] = '';
				while (j < content.length) {
					sharedStrings[i - 1] += unescapeXML(content[j].substring(0, content[j].indexOf('</t>')));
					j++;
				}
			}
			// GrapeCity End
		}
		if (s = zip.file('docProps/core.xml')) { // Get file info from "docProps/core.xml"
			s = s.asText();
			s = s.substr(s.indexOf('<dc:creator>') + 12);
			result.creator = s.substring(0, s.indexOf('</dc:creator>'));
			s = s.substr(s.indexOf('<cp:lastModifiedBy>') + 19);
			result.lastModifiedBy = s.substring(0, s.indexOf('</cp:lastModifiedBy>'));
			s = s.substr(s.indexOf('<dcterms:created xsi:type="dcterms:W3CDTF">') + 43);
			result.created = new Date(s.substring(0, s.indexOf('</dcterms:created>')));
			s = s.substr(s.indexOf('<dcterms:modified xsi:type="dcterms:W3CDTF">') + 44);
			result.modified = new Date(s.substring(0, s.indexOf('</dcterms:modified>')));
		}
		if (s = zip.file('xl/workbook.xml')) { // Get workbook info from "xl/workbook.xml" - Worksheet names exist in other places, but "activeTab" attribute must be gathered from this file anyway
			s = s.asText(); index = s.indexOf('activeTab="');
			if (index > 0) {
				s = s.substr(index + 11); // Must eliminate first 11 characters before finding the index of " on the next line. Otherwise, it finds the " before the value.
				result.activeWorksheet = +s.substring(0, s.indexOf('"'));
			} else { 
				result.activeWorksheet = 0; 
			}
			s = s.split('<sheet '); i = s.length;
			while (--i) { // Do not process i === 0, because s[0] is the text before the first sheet element
				id = s[i].substr(s[i].indexOf('name="') + 6);
				result.sheets.unshift({ name: id.substring(0, id.indexOf('"')), cols: [], rows: [] });
			}
		}
		if (s = zip.file('xl/styles.xml')) { // Get style info from "xl/styles.xml"
			s = s.asText();
			numFmtArray = s.substring(s.indexOf('<numFmts'), s.indexOf('</numFmts>')).split('<numFmt '); i = numFmtArray.length;
			while (--i) { t = numFmtArray[i]; numFmts[+getAttr(t, 'numFmtId')] = getAttr(t, 'formatCode'); }
			// GrapeCity Begin: Add processing for getting font setting.
			fontArray = s.substring(s.indexOf('<fonts'), s.indexOf('</fonts>')).split('<font>'); i = fontArray.length;
			while (--i) {
				t = fontArray[i];
				fonts[i - 1] = {
					bold: t.indexOf('<b/>') !== -1,
					italic: t.indexOf('<i/>') !== -1,
					underline: t.indexOf('<u/>') !== -1,
					size: getChildNodeValue(t, "sz"),
					family: getChildNodeValue(t, "name"),
					color: getColor(t, false)
				}
			}
			// GrapeCity End
			// GrapeCity Begin: Add processing for getting fill setting.
			fillArray = s.substring(s.indexOf('<fills'), s.indexOf('</fills>')).split('<fill>'); i = fillArray.length;
			while (--i) {
				t = fillArray[i];
				fills[i - 1] = getColor(t, true);
			}
			// GrapeCity End

			s = s.substr(s.indexOf('cellXfs')).split('<xf '); i = s.length;
			while (--i) {
				id = getAttr(s[i], 'numFmtId'); f = numFmts[id];
				if (f.indexOf('m') > -1) { t = 'date'; }
				else if (f.indexOf('0') > -1) { t = 'number'; }
				else if (f === '@') { t = 'string'; }
				else { t = 'unknown'; }
				// GrapeCity Begin: Add processing for adding font setting and fill setting and horizontal alignment in the style.
				id = getAttr(s[i], 'fontId'); font = +id > 0 ? fonts[id] : undefined;
				id = getAttr(s[i], 'fillId'); fill = +id > 1 ? fills[id] : undefined;
				styles.unshift({
					formatCode: f,
					type: t,
					font: font,
					fillColor: fill,
					hAlign: s[i].indexOf('<alignment') > -1 ? getAttr(s[i], 'horizontal') : ''
				});
				// GrapeCity End
			}
		}
		result.styles = styles;
		// GrapeCity Begin: Add processing for getting theme color.
		if (s = zip.file('xl/theme/theme1.xml')) {
			s = s.asText();
			themes[0] = getAttr(s.substring(s.indexOf('a:lt1'), s.indexOf('</a:lt1>')), 'lastClr');
			themes[1] = getAttr(s.substring(s.indexOf('a:dk1'), s.indexOf('</a:dk1>')), 'lastClr');
			themes[2] = getAttr(s.substring(s.indexOf('a:lt2'), s.indexOf('</a:lt2>')), 'val');
			themes[3] = getAttr(s.substring(s.indexOf('a:dk2'), s.indexOf('</a:dk2>')), 'val');
			s = s.substring(s.indexOf('<a:accent1'), s.indexOf('</a:accent6>')).split('<a:accent');
			i = s.length;
			while (--i) {
				t = s[i];
				themes[i + 3] = getAttr(t, 'val');
			}
		}
		result.themes = themes;
		// GrapeCity End

		// Get worksheet info from "xl/worksheets/sheetX.xml"
		i = result.sheets.length;
		while (i--) {
			s = zip.file('xl/worksheets/sheet' + (i + 1) + '.xml').asText();
			// GrapeCity Begin: Add merge cells processing.
			mergeCells = [];
			if (s.indexOf('<mergeCells') > -1) {
				mergeCellArray = s.substring(s.indexOf('<mergeCells'), s.indexOf('</mergeCells>')).split('<mergeCell ');
				j = mergeCellArray.length;
				while (--j) {
					mergeRange = getAttr(mergeCellArray[j], 'ref').split(':');
					if (mergeRange.length === 2) {
						mergeCells.unshift({
							topRow: +mergeRange[0].match(/\d*/g).join('') - 1,
							leftCol: alphaNum(mergeRange[0].match(/[a-zA-Z]*/g)[0]),
							bottomRow: +mergeRange[1].match(/\d*/g).join('') - 1,
							rightCol: alphaNum(mergeRange[1].match(/[a-zA-Z]*/g)[0])
						});
					}
				}
			}
			// GrapeCity End
			s = s.split('<row ');
			w = result.sheets[i];
			w.table = s[0].indexOf('<tableParts ') > 0;
			t = getAttr(s[0].substr(s[0].indexOf('<dimension')), 'ref');
			t = t.substr(t.indexOf(':') + 1);
			// GrapeCity Begin: Add hidden column and column width processing. 
			cols = [];
			colsSetting = [];
			hiddenColumns = [];
			if (s.length > 0 && s[0].indexOf('<cols>') > -1) {
				cols = s[0].substring(s[0].indexOf('<cols>') + 6, s[0].indexOf('</cols>')).split('<col ');
				for (idx = cols.length - 1; idx > 0; idx--) {
					colWidth = +getAttr(cols[idx], 'width');
					for (colIndex = +getAttr(cols[idx], 'min') - 1; colIndex < +getAttr(cols[idx], 'max') ; colIndex++) {
						colsSetting[colIndex] = {
							visible: getAttr(cols[idx], 'hidden') !== '1',
							autoWidth: getAttr(cols[idx], 'bestFit') === '1',
							width: colWidth,
							style: {}
						}
					}
				}
			}
			w.cols = colsSetting;
			// GrapeCity End
			// GrapeCity Begin: Add frozen cols/rows processing. 
			if (s.length > 0 && s[0].indexOf('<pane') > -1) {
				if (getAttr(s[0].substr(s[0].indexOf('<pane')), 'state') === 'frozen') {
					frozenRows = getAttr(s[0].substr(s[0].indexOf('<pane')), 'ySplit');
					frozenRows = frozenRows ? +frozenRows : NaN;
					frozenCols = getAttr(s[0].substr(s[0].indexOf('<pane')), 'xSplit');
					frozenCols = frozenCols ? +frozenCols : NaN;
					w.frozenPane = {
						rows: frozenRows,
						columns: frozenCols
					};
				}
			}
			// GrapeCity End
			w.maxCol = alphaNum(t.match(/[a-zA-Z]*/g)[0]) + 1;
			w.maxRow = +t.match(/\d*/g).join('');
			// GrapeCity Begin: Check whether the Group Header is below the group content.
			w.summaryBelow = getAttr(s[0], 'summaryBelow') !== '0';
			// GrapeCity End
			w = w.rows;
			j = s.length;
			while (--j) { // Don't process j === 0, because s[0] is the text before the first row element
				row = w[+getAttr(s[j], 'r') - 1] = { visible: true, groupLevel: NaN, cells: []};
				// GrapeCity Begin: Check the visibility of the row.
				if (s[j].substring(0, s[j].indexOf('>')).indexOf('hidden') > -1 && getAttr(s[j], 'hidden') === '1') {
					row.visible = false;
				}
				// GrapeCity End
				// GrapeCity Begin: Get the row height setting for the custom Height row.
				if (getAttr(s[j], 'customHeight') === '1') {
					height = +getAttr(s[j].substring(0, s[j].indexOf('>')).replace('customHeight', ''), 'ht');
					row.height = height;
				}
				// GrapeCity End
				// GrapeCity Begin: Get the group level.
				groupLevel = getAttr(s[j], 'outlineLevel');
				row.groupLevel = groupLevel && groupLevel !== '' ? +groupLevel : NaN;
				// GrapeCity End
				// GrapeCity Begin: Get the collapsed attribute of the row.
				row.collapsed = getAttr(s[j], 'collapsed') === '1';
				// GrapeCity End
				columns = s[j].split('<c ');
				k = columns.length;
				while (--k) { // Don't process l === 0, because k[0] is the text before the first c (cell) element
					cell = columns[k];
					f = styles[+getAttr(cell, 's')] || { type: 'General', formatCode: 'General' };
					// GrapeCity Begin: set font setting, fill setting and horizontal alignment into the style property.
					if (f.font || f.fillColor || f.hAlign || f.formatCode) {
						style = {
							formatCode: f.formatCode,
							font: f.font,
							fill: {
								color: f.fillColor
							},
							hAlign: f.hAlign
						};
					} else {
						style = undefined;
					}
					// GrapeCity End
					t = getAttr(cell.substring(0, cell.indexOf('>')), 't') || f.type;
					val = cell.substring(cell.indexOf('<v>') + 3, cell.indexOf('</v>'));
					// GrapeCity Begin: Add formula processing. 
					formula = null;
					if (cell.indexOf('</f>') > -1) {
						formula = cell.match(/\<f.*>.+(?=\<\/f>)/);
						formula = formula ? formula[0].replace(/(\<f.*>)(.+)/, "$2") : '';
					}
					// GrapeCity End
					// GrapeCity Begin: Fix issue that couldn't read the excel cell content processed by the string processing function.
					if (t !== 'str') { val = val ? +val : ''; } // turn non-zero into number when the type of the cell is not 'str'
					// GrapeCity End
					colIndex = alphaNum(getAttr(cell, 'r').match(/[a-zA-Z]*/g)[0]);
					switch (t) {
						case 's': val = sharedStrings[val]; break;
						case 'b': val = val === 1; break;
						case 'date': val = convertDate(val); break;
					}
					row.cells[colIndex] = {
						value: val,
						formula: unescapeXML(formula) /* GrapeCity: Add formula property.*/,
						style: style, /* GrapeCity: Add style property.*/
						visible: hiddenColumns.indexOf(colIndex) === -1 /* Add visible property for the cell */
					};
				}
			}
			// GrapeCity Begin: Parse the merge cell info to rowSpan and colSpan property of cell.
			for (k = 0; k < mergeCells.length; k++) {
				mergeCell = mergeCells[k];
				result.sheets[i].rows[mergeCell.topRow].cells[mergeCell.leftCol].rowSpan = mergeCell.bottomRow - mergeCell.topRow + 1;
				result.sheets[i].rows[mergeCell.topRow].cells[mergeCell.leftCol].colSpan = mergeCell.rightCol - mergeCell.leftCol + 1;
			}
			// GrapeCity End.
		}

		result.processTime = Date.now() - processTime;
	}
	else { // Save
		processTime = Date.now();
		sharedStrings = [[], 0];
		// Fully static
		zip.folder('_rels').file('.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
		docProps = zip.folder('docProps');

		xl = zip.folder('xl');
		xl.folder('theme').file('theme1.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><a:theme xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" name="Office Theme"><a:themeElements><a:clrScheme name="Office"><a:dk1><a:sysClr val="windowText" lastClr="000000"/></a:dk1><a:lt1><a:sysClr val="window" lastClr="FFFFFF"/></a:lt1><a:dk2><a:srgbClr val="1F497D"/></a:dk2><a:lt2><a:srgbClr val="EEECE1"/></a:lt2><a:accent1><a:srgbClr val="4F81BD"/></a:accent1><a:accent2><a:srgbClr val="C0504D"/></a:accent2><a:accent3><a:srgbClr val="9BBB59"/></a:accent3><a:accent4><a:srgbClr val="8064A2"/></a:accent4><a:accent5><a:srgbClr val="4BACC6"/></a:accent5><a:accent6><a:srgbClr val="F79646"/></a:accent6><a:hlink><a:srgbClr val="0000FF"/></a:hlink><a:folHlink><a:srgbClr val="800080"/></a:folHlink></a:clrScheme><a:fontScheme name="Office"><a:majorFont><a:latin typeface="Cambria"/><a:ea typeface=""/><a:cs typeface=""/><a:font script="Jpan" typeface="MS P????"/><a:font script="Hang" typeface="?? ??"/><a:font script="Hans" typeface="??"/><a:font script="Hant" typeface="????"/><a:font script="Arab" typeface="Times New Roman"/><a:font script="Hebr" typeface="Times New Roman"/><a:font script="Thai" typeface="Tahoma"/><a:font script="Ethi" typeface="Nyala"/><a:font script="Beng" typeface="Vrinda"/><a:font script="Gujr" typeface="Shruti"/><a:font script="Khmr" typeface="MoolBoran"/><a:font script="Knda" typeface="Tunga"/><a:font script="Guru" typeface="Raavi"/><a:font script="Cans" typeface="Euphemia"/><a:font script="Cher" typeface="Plantagenet Cherokee"/><a:font script="Yiii" typeface="Microsoft Yi Baiti"/><a:font script="Tibt" typeface="Microsoft Himalaya"/><a:font script="Thaa" typeface="MV Boli"/><a:font script="Deva" typeface="Mangal"/><a:font script="Telu" typeface="Gautami"/><a:font script="Taml" typeface="Latha"/><a:font script="Syrc" typeface="Estrangelo Edessa"/><a:font script="Orya" typeface="Kalinga"/><a:font script="Mlym" typeface="Kartika"/><a:font script="Laoo" typeface="DokChampa"/><a:font script="Sinh" typeface="Iskoola Pota"/><a:font script="Mong" typeface="Mongolian Baiti"/><a:font script="Viet" typeface="Times New Roman"/><a:font script="Uigh" typeface="Microsoft Uighur"/><a:font script="Geor" typeface="Sylfaen"/></a:majorFont><a:minorFont><a:latin typeface="Calibri"/><a:ea typeface=""/><a:cs typeface=""/><a:font script="Jpan" typeface="MS P????"/><a:font script="Hang" typeface="?? ??"/><a:font script="Hans" typeface="??"/><a:font script="Hant" typeface="????"/><a:font script="Arab" typeface="Arial"/><a:font script="Hebr" typeface="Arial"/><a:font script="Thai" typeface="Tahoma"/><a:font script="Ethi" typeface="Nyala"/><a:font script="Beng" typeface="Vrinda"/><a:font script="Gujr" typeface="Shruti"/><a:font script="Khmr" typeface="DaunPenh"/><a:font script="Knda" typeface="Tunga"/><a:font script="Guru" typeface="Raavi"/><a:font script="Cans" typeface="Euphemia"/><a:font script="Cher" typeface="Plantagenet Cherokee"/><a:font script="Yiii" typeface="Microsoft Yi Baiti"/><a:font script="Tibt" typeface="Microsoft Himalaya"/><a:font script="Thaa" typeface="MV Boli"/><a:font script="Deva" typeface="Mangal"/><a:font script="Telu" typeface="Gautami"/><a:font script="Taml" typeface="Latha"/><a:font script="Syrc" typeface="Estrangelo Edessa"/><a:font script="Orya" typeface="Kalinga"/><a:font script="Mlym" typeface="Kartika"/><a:font script="Laoo" typeface="DokChampa"/><a:font script="Sinh" typeface="Iskoola Pota"/><a:font script="Mong" typeface="Mongolian Baiti"/><a:font script="Viet" typeface="Arial"/><a:font script="Uigh" typeface="Microsoft Uighur"/><a:font script="Geor" typeface="Sylfaen"/></a:minorFont></a:fontScheme><a:fmtScheme name="Office"><a:fillStyleLst><a:solidFill><a:schemeClr val="phClr"/></a:solidFill><a:gradFill rotWithShape="1"><a:gsLst><a:gs pos="0"><a:schemeClr val="phClr"><a:tint val="50000"/><a:satMod val="300000"/></a:schemeClr></a:gs><a:gs pos="35000"><a:schemeClr val="phClr"><a:tint val="37000"/><a:satMod val="300000"/></a:schemeClr></a:gs><a:gs pos="100000"><a:schemeClr val="phClr"><a:tint val="15000"/><a:satMod val="350000"/></a:schemeClr></a:gs></a:gsLst><a:lin ang="16200000" scaled="1"/></a:gradFill><a:gradFill rotWithShape="1"><a:gsLst><a:gs pos="0"><a:schemeClr val="phClr"><a:shade val="51000"/><a:satMod val="130000"/></a:schemeClr></a:gs><a:gs pos="80000"><a:schemeClr val="phClr"><a:shade val="93000"/><a:satMod val="130000"/></a:schemeClr></a:gs><a:gs pos="100000"><a:schemeClr val="phClr"><a:shade val="94000"/><a:satMod val="135000"/></a:schemeClr></a:gs></a:gsLst><a:lin ang="16200000" scaled="0"/></a:gradFill></a:fillStyleLst><a:lnStyleLst><a:ln w="9525" cap="flat" cmpd="sng" algn="ctr"><a:solidFill><a:schemeClr val="phClr"><a:shade val="95000"/><a:satMod val="105000"/></a:schemeClr></a:solidFill><a:prstDash val="solid"/></a:ln><a:ln w="25400" cap="flat" cmpd="sng" algn="ctr"><a:solidFill><a:schemeClr val="phClr"/></a:solidFill><a:prstDash val="solid"/></a:ln><a:ln w="38100" cap="flat" cmpd="sng" algn="ctr"><a:solidFill><a:schemeClr val="phClr"/></a:solidFill><a:prstDash val="solid"/></a:ln></a:lnStyleLst><a:effectStyleLst><a:effectStyle><a:effectLst><a:outerShdw blurRad="40000" dist="20000" dir="5400000" rotWithShape="0"><a:srgbClr val="000000"><a:alpha val="38000"/></a:srgbClr></a:outerShdw></a:effectLst></a:effectStyle><a:effectStyle><a:effectLst><a:outerShdw blurRad="40000" dist="23000" dir="5400000" rotWithShape="0"><a:srgbClr val="000000"><a:alpha val="35000"/></a:srgbClr></a:outerShdw></a:effectLst></a:effectStyle><a:effectStyle><a:effectLst><a:outerShdw blurRad="40000" dist="23000" dir="5400000" rotWithShape="0"><a:srgbClr val="000000"><a:alpha val="35000"/></a:srgbClr></a:outerShdw></a:effectLst><a:scene3d><a:camera prst="orthographicFront"><a:rot lat="0" lon="0" rev="0"/></a:camera><a:lightRig rig="threePt" dir="t"><a:rot lat="0" lon="0" rev="1200000"/></a:lightRig></a:scene3d><a:sp3d><a:bevelT w="63500" h="25400"/></a:sp3d></a:effectStyle></a:effectStyleLst><a:bgFillStyleLst><a:solidFill><a:schemeClr val="phClr"/></a:solidFill><a:gradFill rotWithShape="1"><a:gsLst><a:gs pos="0"><a:schemeClr val="phClr"><a:tint val="40000"/><a:satMod val="350000"/></a:schemeClr></a:gs><a:gs pos="40000"><a:schemeClr val="phClr"><a:tint val="45000"/><a:shade val="99000"/><a:satMod val="350000"/></a:schemeClr></a:gs><a:gs pos="100000"><a:schemeClr val="phClr"><a:shade val="20000"/><a:satMod val="255000"/></a:schemeClr></a:gs></a:gsLst><a:path path="circle"><a:fillToRect l="50000" t="-80000" r="50000" b="180000"/></a:path></a:gradFill><a:gradFill rotWithShape="1"><a:gsLst><a:gs pos="0"><a:schemeClr val="phClr"><a:tint val="80000"/><a:satMod val="300000"/></a:schemeClr></a:gs><a:gs pos="100000"><a:schemeClr val="phClr"><a:shade val="30000"/><a:satMod val="200000"/></a:schemeClr></a:gs></a:gsLst><a:path path="circle"><a:fillToRect l="50000" t="50000" r="50000" b="50000"/></a:path></a:gradFill></a:bgFillStyleLst></a:fmtScheme></a:themeElements><a:objectDefaults/><a:extraClrSchemeLst/></a:theme>');
		xlWorksheets = xl.folder('worksheets');

		// Not content dependent
		docProps.file('core.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><dc:creator>'
			+ (file.creator || 'XLSX.js') + '</dc:creator><cp:lastModifiedBy>' + (file.lastModifiedBy || 'XLSX.js') + '</cp:lastModifiedBy><dcterms:created xsi:type="dcterms:W3CDTF">'
			+ (file.created || new Date()).toISOString() + '</dcterms:created><dcterms:modified xsi:type="dcterms:W3CDTF">' + (file.modified || new Date()).toISOString() + '</dcterms:modified></cp:coreProperties>');

		// Content dependent
		styles = new Array(1);
		borders = new Array(1);
		fonts = new Array(1);
		fills = new Array(2); /* GrapeCity: Initialize the fills array for fill color processing.*/
		
		w = file.sheets.length;
		while (w--) { 
			// Generate worksheet (gather sharedStrings), and possibly table files, then generate entries for constant files below
			id = w + 1;
			// Generate sheetX.xml in var s
			worksheet = file.sheets[w]; data = worksheet.rows;
			// GrapeCity Begin: Add frozen cols/rows processing. 
			frozenPane = '';
			if (worksheet.frozenPane && (worksheet.frozenPane.rows !== 0 || worksheet.frozenPane.columns !== 0)) {
				frozenPane = '<pane state="frozen" activePane="' +
					(worksheet.frozenPane.rows !== 0 && worksheet.frozenPane.columns !== 0 ? 'bottomRight' :
					(worksheet.frozenPane.rows !== 0 ? 'bottomLeft' : 'topRight')) +
					'" topLeftCell="' + numAlpha(worksheet.frozenPane.columns) + (worksheet.frozenPane.rows + 1) +
					'" ySplit="' + worksheet.frozenPane.rows + '" xSplit="' + worksheet.frozenPane.columns + '"/>';
			}
			// GrapeCity End
			s = '';
			columns = [];
			merges = [];
			i = -1; l = data.length;
			while (++i < l) {
				j = -1; k = data[i].cells.length;
				// GrapeCity Begin: Add row visibility, row height and group level for current excel row.
				rowHeightSetting = '';
				groupLevelSetting = '';
				if (k > 0) {
					if (data[i].height) {
						rowHeightSetting = 'customHeight="1" ht="' + data[i].height + '"';
					}
					if (data[i].groupLevel) {
						groupLevelSetting = 'outlineLevel=' + '"' + data[i].groupLevel + '"';
					}
				}
				rowStr = '<row r="' + (i + 1) + '" {rowHeight} x14ac:dyDescent="0.25" {groupLevel} ' +
					(!data[i].visible ? 'hidden="1"' : '') + '>';
				rowStr = rowStr.replace('{rowHeight}', rowHeightSetting);
				rowStr = rowStr.replace('{groupLevel}', groupLevelSetting);
				s += rowStr;
				// GrapeCity End
				while (++j < k) {
					cell = data[i].cells[j]; val = cell.hasOwnProperty('value') ? cell.value : cell; t = '';
					style = cell.style; /* GrapeCity: Packed cell styles into the style property of cell */
					style.hAlign = style.hAlign || worksheet.cols[j].style.hAlign;
					colWidth = 0;

					if (val && typeof val === 'string' && (+val).toString() !== val) {
						// If value is string, and not string of just a number, place a sharedString reference instead of the value
						val = escapeXML(val);
						sharedStrings[1]++; // Increment total count, unique count derived from sharedStrings[0].length
						index = sharedStrings[0].indexOf(val);
						// GrapeCity: Add width property for the cell, that user can customize the width of the column for exporting.
						colWidth = worksheet.cols[j].width || val.length;
						if (index < 0) {
							index = sharedStrings[0].push(val) - 1;
						}
						val = index;
						t = 's';
					}
					else if (typeof val === 'boolean') {
						val = (val ? 1 : 0); t = 'b';
						// GrapeCity: Add width property for the cell, that user can customize the width of the column for exporting.
						colWidth = worksheet.cols[j].width || 1;
					}
					else if (typeOf(val) === 'date') {
						val = convertDate(val);
						style.formatCode = cell.style.formatCode || 'mm-dd-yy';
						// GrapeCity: Add width property for the cell, that user can customize the width of the column for exporting.
						colWidth = worksheet.cols[j].width || val.length;
					}
					else if (typeof val === 'object') { val = null; } // unsupported value
					// GrapeCity: Add width property for the cell, that user can customize the width of the column for exporting.
					else { colWidth = worksheet.cols[j].width || ('' + val).length; } // number, or string which is a number
					
					// use stringified version as unic and reproductible style signature
					style = JSON.stringify(style);
					index = styles.indexOf(style);
					if (index < 0) { style = styles.push(style) - 1; }
					else { style = index; }
					// keeps largest cell in column
					if (colWidth > worksheet.cols[j].width) { worksheet.cols[j].width = colWidth; }
					// store merges if needed and add missing cells. Cannot have rowSpan AND colSpan
					// GrapeCity Begin: Update for merge cells processing.
					if (cell.colSpan > 1 || cell.rowSpan > 1) {
						merged = [j, 0];
						// horizontal merge. ex: B12:E12. Add missing cells (with same attribute but value) to current row.
						for (var m = 0; m < cell.colSpan - 1; m++) {
							merged.push(cell);
						}
						data[i].cells.splice.apply(data[i].cells, merged);
						k += cell.colSpan - 1;
						// vertical merge. ex: B12:B15. Add missing cells (with same attribute but value) to next row.
						merged.push(cell);
						for (var n = 1; n < cell.rowSpan; n++) {
							if (data[i + n]) {
								data[i + n].cells.splice.apply(data[i + n].cells, merged);
							} else {
								// readh the end of data
								cell.rowSpan = n;
								break;
							}
						}
						merges.push([numAlpha(j) + (i + 1), numAlpha(j + cell.colSpan - 1) + (i + cell.rowSpan)]);
						// deletes value, rowSpan and colSpan from cell to avoid refering it from copied cells
						delete cell.value;
						delete cell.rowSpan;
						delete cell.colSpan;
					}
					// GrapeCity End
					s += '<c r="' + numAlpha(j) + (i + 1) + '"' + (style ? ' s="' + style + '"' : '') + (t ? ' t="' + t + '"' : '');
					if (val != null) {
						// add 'ca' attribute for the cellFormula element.
						s += '>' + (cell.formula ? '<f ca="1">' + escapeXML(cell.formula) /* GrapeCity: escapeXML for the formula of cell.*/ + '</f>' : '') + '<v>' + val + '</v></c>';
					} else {
						s += '/>';
					}
				}
				s += '</row>';
			}

			cols = [];
			for (i = 0; i < worksheet.cols.length; i++) {
				// GrapeCity Begin: Add the column visibilty for the excel column
				if (worksheet.cols[i].autoWidth) {
					cols.push('<col min="', i + 1, '" max="', i + 1, '" width="', worksheet.cols[i].width, '" bestFit="1" ',
						(worksheet.cols[i].visible ? '' : 'hidden="1"'), '/>');
				} else if (!worksheet.cols[i].visible) {
					cols.push('<col min="', i + 1, '" max="', i + 1, '" bestFit="1" hidden="1"/>');
				}
				// GrapeCity End
			}
			// only add cols definition if not empty
			if (cols.length > 0) {
				cols = ['<cols>'].concat(cols, ['</cols>']).join('');
			}

			l = data[0] ? data[0].cells.length : 0;
			s = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac">'
				/* GrapeCity: Add summaryBelow property for displaying the group header above the group contents*/
				+ '<sheetPr><outlinePr summaryBelow="0"/></sheetPr>'
				/* GrapeCity: Fix issue exporting empty sheet 'dimension ref' property incorrect.*/
				+ '<dimension ref="A1' + (l > 0 ? ':' + numAlpha(l - 1) + (data.length) : '')
				+ '"/><sheetViews><sheetView ' + (w === file.activeWorksheet ? 'tabSelected="1" ' : '')
				/* GrapeCity: Add frozen pane setting.*/
				+ ' workbookViewId="0">' + frozenPane + '</sheetView>'
				+ '</sheetViews><sheetFormatPr defaultRowHeight="15" x14ac:dyDescent="0.25"/>'
				+ cols
				+ '<sheetData>'
				+ s 
				+ '</sheetData>';
			if (merges.length > 0) {
				s += '<mergeCells count="' + merges.length + '">';
				for (i = 0; i < merges.length; i++) {
					s += '<mergeCell ref="' + merges[i].join(':') + '"/>';
				}
				s += '</mergeCells>';
			}
			s += '<pageMargins left="0.7" right="0.7" top="0.75" bottom="0.75" header="0.3" footer="0.3"/>';
			if (worksheet.table) { 
				s += '<tableParts count="1"><tablePart r:id="rId1"/></tableParts>'; 
			}
			xlWorksheets.file('sheet' + id + '.xml', s + '</worksheet>');

			if (worksheet.table) {
				i = -1; 
				/* GrapeCity: Fix issue exporting empty sheet 'dimension ref' property incorrect.*/
				t = l > 0 ? ':' + numAlpha(l - 1) + data.length : '';
				s = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><table xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" id="' + id
					+ '" name="Table' + id + '" displayName="Table' + id + '" ref="A1' + t + '" totalsRowShown="0"><autoFilter ref="A1' + t + '"/><tableColumns count="' + l + '">';
				while (++i < l) { 
					s += '<tableColumn id="' + (i + 1) + '" name="' + (data[0].cells[i].hasOwnProperty('value') ? data[0].cells[i].value : data[0].cells[i]) + '"/>'; 
				}
				s += '</tableColumns><tableStyleInfo name="TableStyleMedium2" showFirstColumn="0" showLastColumn="0" showRowStripes="1" showColumnStripes="0"/></table>';

				xl.folder('tables').file('table' + id + '.xml', s); 
				xlWorksheets.folder('_rels').file('sheet' + id + '.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/table" Target="../tables/table' + id + '.xml"/></Relationships>');
				contentTypes[1].unshift('<Override PartName="/xl/tables/table' + id + '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.table+xml"/>');
			}

			contentTypes[0].unshift('<Override PartName="/xl/worksheets/sheet' + id + '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>');
			props.unshift(escapeXML(worksheet.name) || 'Sheet' + id);
			xlRels.unshift('<Relationship Id="rId' + id + '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' + id + '.xml"/>');
			worksheets.unshift('<sheet name="' + (escapeXML(worksheet.name) || 'Sheet' + id) + '" sheetId="' + id + '" r:id="rId' + id + '"/>');
		}

		// xl/styles.xml
		i = styles.length; t = [];
		while (--i) { 
			// Don't process index 0, already added
			style = JSON.parse(styles[i]);

			// cell formating, refer to it if necessary
			if (style.formatCode && style.formatCode !== 'General') {
				index = numFmts.indexOf(style.formatCode);
				if (index < 0) { 
					index = 164 + t.length; 
					t.push('<numFmt formatCode="' + style.formatCode + '" numFmtId="' + index + '"/>'); 
				}
				style.formatCode = index;
			} else {
				style.formatCode = 0;
			}

			// border declaration: add a new declaration and refer to it in style
			borderIndex = 0;
			if (style.borders) {
				border = ['<border>']
				// order is significative
				for (var edge in {left:0, right:0, top:0, bottom:0, diagonal:0}) {
					if (style.borders[edge]) {
						var color = style.borders[edge];
						// add transparency if missing
						if (color.length === 6) {
							color = 'FF'+color;
						}
						border.push('<', edge, ' style="thin">', '<color rgb="', style.borders[edge], '"/></', edge, '>');
					} else {
						border.push('<', edge, '/>');
					}
				}
				border.push('</border>');
				border = border.join('');
				// try to reuse existing border
				borderIndex = borders.indexOf(border);
				if (borderIndex < 0) {
					borderIndex = borders.push(border) - 1;
				}
			}

			// font declaration: add a new declaration and refer to it in style
			fontIndex = 0;
			if (style.font) {
				font = ['<font>']
				if (style.font.bold) {
					font.push('<b/>');
				}
				if (style.font.italic) {
					font.push('<i/>');
				}
				// GrapeCity Begin: Add underline property for font
				if (style.font.underline) {
					font.push('<u/>');
				}
				// GrapeCity End
				font.push('<sz val="', style.font.size || defaultFontSize, '"/>');
				// GrapeCity Begin: Add color property for font
				font.push('<color ', style.font.color ? 'rgb="FF' + (style.font.color[0] === '#' ? style.font.color.substring(1) : style.font.color) + '"' : 'theme="1"', '/>');
				// GrapeCity End
				font.push('<name val="', style.font.family || defaultFontName, '"/>');
				font.push('<family val="2"/>', '</font>');
				font = font.join('');
				// try to reuse existing font
				fontIndex = fonts.indexOf(font);
				if (fontIndex < 0) {
					fontIndex = fonts.push(font) - 1;
				}
			}

			// GrapeCity Begin: Add fill color property
			fillIndex = 0;
			if (style.fill && style.fill.color) {
				fill = ['<fill><patternFill patternType="solid">'];
				fill.push('<fgColor rgb="FF', (style.fill.color[0] === '#' ? style.fill.color.substring(1) : style.fill.color), '"/>');
				fill.push('<bgColor indexed="64"/>');
				fill.push('</patternFill></fill>');
				fill = fill.join('');
				fillIndex = fills.indexOf(fill);
				if (fillIndex < 0) {
					fillIndex = fills.push(fill) - 1;
				}
			}
			// GrapeCity End

			// declares style, and refer to optionnal formatCode, font and borders
			styles[i] = ['<xf xfId="0" borderId="', 
				borderIndex, 
				'" fontId="',
				fontIndex,
				// GrapeCity Begin: Add fill color property
				'" fillId="',
				fillIndex,
				// GrapeCity End
				'" numFmtId="',
				style.formatCode,
				'" ',
				(style.hAlign || style.vAlign? 'applyAlignment="1" ' : ' '),
				(style.formatCode > 0 ? 'applyNumberFormat="1" ' : ' '),
				(borderIndex > 0 ? 'applyBorder="1" ' : ' '),
				(fontIndex > 0 ? 'applyFont="1" ' : ' '),
				// GrapeCity Begin: Add fill color property
				(fillIndex > 0 ? 'applyFill="1" ' : ' '),
				// GrapeCity End
				'>'
			];
			if (style.hAlign || style.vAlign || style.indent) {
				styles[i].push('<alignment');
				if (style.hAlign) {
					styles[i].push(' horizontal="', style.hAlign, '"');
				}
				if (style.vAlign) {
					styles[i].push(' vertical="', style.vAlign, '"');
				}
				// GrapeCity Begin: Add indent property for the nested group
				if (style.indent) {
					styles[i].push(' indent="', style.indent, '"');
				}
				// GrapeCity End
				styles[i].push('/>');
			}
			styles[i].push('</xf>');
			styles[i] = styles[i].join('');
		}
		t = t.length ? '<numFmts count="' + t.length + '">' + t.join('') + '</numFmts>' : '';

		xl.file('styles.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac">'
			+ t + '<fonts count="'+ fonts.length + '" x14ac:knownFonts="1"><font><sz val="' + defaultFontSize + '"/><color theme="1"/><name val="' + defaultFontName + '"/><family val="2"/>'
			+ '<scheme val="minor"/></font>' + fonts.join('') + '</fonts><fills count="' + fills.length + '"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill>'
			+ fills.join('') + '</fills>' /* GrapeCity: Add fill color settings.*/
			+ '<borders count="' + borders.length + '"><border><left/><right/><top/><bottom/><diagonal/></border>'
			+ borders.join('') + '</borders><cellStyleXfs count="1">'
			+ '<xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs><cellXfs count="' + styles.length + '"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
			+ styles.join('') + '</cellXfs><cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles><dxfs count="0"/>'
			+ '<tableStyles count="0" defaultTableStyle="TableStyleMedium2" defaultPivotStyle="PivotStyleLight16"/>'
			+ '<extLst><ext uri="{EB79DEF2-80B8-43e5-95BD-54CBDDF9020C}" xmlns:x14="http://schemas.microsoft.com/office/spreadsheetml/2009/9/main">'
			+ '<x14:slicerStyles defaultSlicerStyle="SlicerStyleLight1"/></ext></extLst></styleSheet>');

		// [Content_Types].xml
		zip.file('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
			+ contentTypes[0].join('') + '<Override PartName="/xl/theme/theme1.xml" ContentType="application/vnd.openxmlformats-officedocument.theme+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/><Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>'
			+ contentTypes[1].join('') + '<Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/><Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/></Types>');

		// docProps/app.xml
		docProps.file('app.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes"><Application>XLSX.js</Application><DocSecurity>0</DocSecurity><ScaleCrop>false</ScaleCrop><HeadingPairs><vt:vector size="2" baseType="variant"><vt:variant><vt:lpstr>Worksheets</vt:lpstr></vt:variant><vt:variant><vt:i4>'
			+ file.sheets.length + '</vt:i4></vt:variant></vt:vector></HeadingPairs><TitlesOfParts><vt:vector size="' + props.length + '" baseType="lpstr"><vt:lpstr>' + props.join('</vt:lpstr><vt:lpstr>')
			+ '</vt:lpstr></vt:vector></TitlesOfParts><Manager></Manager><Company>Microsoft Corporation</Company><LinksUpToDate>false</LinksUpToDate><SharedDoc>false</SharedDoc><HyperlinksChanged>false</HyperlinksChanged><AppVersion>1.0</AppVersion></Properties>');

		// xl/_rels/workbook.xml.rels
		xl.folder('_rels').file('workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
			+ xlRels.join('') + '<Relationship Id="rId' + (xlRels.length + 1) + '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>'
			+ '<Relationship Id="rId' + (xlRels.length + 2) + '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
			+ '<Relationship Id="rId' + (xlRels.length + 3) + '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme" Target="theme/theme1.xml"/></Relationships>');

		// xl/sharedStrings.xml
		xl.file('sharedStrings.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="'
			+ sharedStrings[1] + '" uniqueCount="' + sharedStrings[0].length + '"><si><t>' + sharedStrings[0].join('</t></si><si><t>') + '</t></si></sst>');

		// xl/workbook.xml
		xl.file('workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
			+ '<fileVersion appName="xl" lastEdited="5" lowestEdited="5" rupBuild="9303"/><workbookPr defaultThemeVersion="124226"/><bookViews><workbookView '
			+ (file.activeWorksheet ? 'activeTab="' + file.activeWorksheet + '" ' : '') + 'xWindow="480" yWindow="60" windowWidth="18195" windowHeight="8505"/></bookViews><sheets>'
			+ worksheets.join('') + '</sheets><calcPr fullCalcOnLoad="1"/></workbook>');

		processTime = Date.now() - processTime;
		zipTime = Date.now();
		result = {
			base64: zip.generate({ compression: 'DEFLATE' }), zipTime: Date.now() - zipTime, processTime: processTime,
			href: function() { return 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' + this.base64; }
		};
	}
	return result;
}

if (typeof exports === 'object' && typeof module === 'object') { module.exports = xlsx; } // NodeJs export