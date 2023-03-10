var wijmo;
(function (wijmo) {
    (function (_grid) {
        'use strict';

        /**
        * ExcelConverter class provides function exporting FlexGrid to xlsx file
        * and importing xlsx file to FlexGrid.
        */
        var ExcelConverter = (function () {
            function ExcelConverter() {
            }
            /**
            * export the FlexGrid to xlsx file
            *
            * @param flex the FlexGrid need be exported to xlsx file
            * @param convertOption the options for exporting.
            */
            ExcelConverter.export = function (flex, convertOption) {
                if (typeof convertOption === "undefined") { convertOption = { includeColumnHeader: true }; }
                var file, result;

                file = this.parseFlexGridToCommonFormat(flex, convertOption);
                result = xlsx(file);

                result.base64Array = this._base64DecToArr(result.base64);

                return result;
            };

            /**
            * import the xlsx file
            *
            * @param file the base64 string converted from xlsx file
            * @param flex the Flex Grid need bind the data import from xlsx file
            * @param convertOption the options for importing.
            * @param moreSheets flexgrid array for importing multiple sheets of the excel file.
            */
            ExcelConverter.import = function (file, flex, convertOption, moreSheets) {
                if (typeof convertOption === "undefined") { convertOption = { includeColumnHeader: true }; }
                var fileData = this._base64EncArr(new Uint8Array(file)), fileObj = xlsx(fileData);

                this.parseCommonFormatToFlexGrid(fileObj, flex, convertOption, moreSheets);
            };

            /**
            * Parse the flexgrid to common format of xlsx file.
            *
            * @param flex the FlexGrid need be parsed to  common format of xlsx file
            * @param convertOption the options for exporting.
            */
            ExcelConverter.parseFlexGridToCommonFormat = function (flex, convertOption) {
                if (typeof convertOption === "undefined") { convertOption = { includeColumnHeader: true }; }
                var file = {
                    sheets: [],
                    creator: 'Mike Lu',
                    created: new Date(),
                    lastModifiedBy: 'Mike Lu',
                    modified: new Date(),
                    activeWorksheet: 0
                }, index, grid;

                if (wijmo.isArray(flex)) {
                    for (index = 0; index < flex.length; index++) {
                        grid = flex[index];

                        // export the FlexGrid to xlsx.
                        this._exportFlexGrid(grid, file, convertOption);
                    }
                } else {
                    this._exportFlexGrid(flex, file, convertOption);
                }

                return file;
            };

            /**
            * Parse the common format of the xlsx file to flexgrid.
            *
            * @param fileObj the common format of the xlsx file.
            * @param flex the Flex Grid need bind the data import from xlsx file.
            * @param convertOption the options for importing.
            * @param moreSheets flexgrid array for importing multiple sheets of the excel file.
            */
            ExcelConverter.parseCommonFormatToFlexGrid = function (fileObj, flex, convertOption, moreSheets) {
                if (typeof convertOption === "undefined") { convertOption = { includeColumnHeader: true }; }
                var currentIncludeRowHeader = convertOption.includeColumnHeader, sheetCount = 1, sheetIndex = 0, c = 0, r = 0, columns, columnSetting, column, columnHeader, sheetHeaders, sheetHeader, headerForamt, row, currentSheet, columnCount, isGroupHeader, item, nextRowIdx, nextRow, summaryBelow, groupRow, frozenColumns, frozenRows, formula, flexHostElement, cellIndex, cellStyle, styledCells, excelThemes, mergedRange, fonts, valType, textAlign, groupCollapsed = false;

                flex.columns.clear();
                flex.rows.clear();
                flex.frozenColumns = 0;
                flex.frozenRows = 0;

                if (fileObj.sheets.length === 0) {
                    return;
                }

                excelThemes = fileObj.themes;

                if (moreSheets) {
                    sheetCount = fileObj.sheets.length;
                }

                for (; sheetIndex < sheetCount; sheetIndex++) {
                    styledCells = {};
                    mergedRange = {};
                    r = 0;
                    columns = [];
                    fonts = [];
                    currentSheet = fileObj.sheets[sheetIndex];

                    if (convertOption.includeColumnHeader) {
                        r = 1;
                        if (currentSheet.rows.length <= 1) {
                            currentIncludeRowHeader = false;
                            r = 0;
                        }
                        sheetHeaders = currentSheet.rows[0];
                    }
                    columnCount = this._getColumnCount(currentSheet.rows);
                    summaryBelow = currentSheet.summaryBelow;

                    if (sheetIndex > 0) {
                        flexHostElement = document.createElement('div');
                        flex = new _grid.FlexGrid(flexHostElement);
                    }

                    for (c = 0; c < columnCount; c++) {
                        flex.columns.push(new wijmo.grid.Column());
                        if (!!currentSheet.cols[c]) {
                            if (!isNaN(currentSheet.cols[c].width)) {
                                flex.columns[c].width = currentSheet.cols[c].width * 6;
                            }

                            if (!currentSheet.cols[c].visible && currentSheet.cols[c].visible != undefined) {
                                flex.columns[c].visible = !!currentSheet.cols[c].visible;
                            }
                        }
                    }

                    for (; r < currentSheet.rows.length; r++) {
                        isGroupHeader = false;
                        row = currentSheet.rows[r];

                        if (row) {
                            nextRowIdx = r + 1;
                            while (nextRowIdx < currentSheet.rows.length) {
                                nextRow = currentSheet.rows[nextRowIdx];
                                if (nextRow) {
                                    if ((isNaN(row.groupLevel) && !isNaN(nextRow.groupLevel)) || (!isNaN(row.groupLevel) && row.groupLevel < nextRow.groupLevel)) {
                                        isGroupHeader = true;
                                    }
                                    break;
                                } else {
                                    nextRowIdx++;
                                }
                            }
                        }

                        if (isGroupHeader && !summaryBelow) {
                            groupRow = new _grid.GroupRow();
                            groupCollapsed = row.collapsed;
                            groupRow.level = isNaN(row.groupLevel) ? 0 : row.groupLevel;
                            flex.rows.push(groupRow);
                        } else {
                            flex.rows.push(new _grid.Row());
                        }

                        if (row && !!row.height && !isNaN(row.height)) {
                            flex.rows[currentIncludeRowHeader ? r - 1 : r].height = row.height * 96 / 72;
                        }

                        for (c = 0; c < columnCount; c++) {
                            if (!row) {
                                flex.setCellData(currentIncludeRowHeader ? r - 1 : r, c, '');
                                this._setColumn(columns, c, undefined);
                            } else {
                                item = row.cells[c];
                                formula = item ? item.formula : undefined;
                                if (formula && formula[0] !== '=') {
                                    formula = '=' + formula;
                                }
                                formula = formula ? this._parseFlexSheetFormula(formula) : undefined;
                                flex.setCellData(currentIncludeRowHeader ? r - 1 : r, c, formula && moreSheets ? formula : this._getItemValue(item));
                                if (!isGroupHeader && item && item.value != null && item.value !== '') {
                                    this._setColumn(columns, c, item);
                                }

                                // Set styles for the cell in current processing sheet.
                                cellIndex = r * columnCount + c;
                                cellStyle = item ? item.style : undefined;
                                if (cellStyle) {
                                    valType = this._getItemType(item);
                                    if (cellStyle.hAlign) {
                                        textAlign = cellStyle.hAlign;
                                    } else {
                                        switch (valType) {
                                            case 2 /* Number */:
                                                textAlign = 'right';
                                                break;
                                            case 3 /* Boolean */:
                                                textAlign = 'center';
                                                break;
                                            default:
                                                textAlign = 'left';
                                                break;
                                        }
                                    }
                                    styledCells[cellIndex] = {
                                        fontWeight: cellStyle.font && cellStyle.font.bold ? 'bold' : 'none',
                                        fontStyle: cellStyle.font && cellStyle.font.italic ? 'italic' : 'none',
                                        textDecoration: cellStyle.font && cellStyle.font.underline ? 'underline' : 'none',
                                        textAlign: textAlign,
                                        fontFamily: cellStyle.font && cellStyle.font.family ? cellStyle.font.family : '',
                                        fontSize: cellStyle.font && cellStyle.font.size ? Math.round(+cellStyle.font.size * 96 / 72) + 'px' : '',
                                        color: cellStyle.font ? this._parseExcelColor(cellStyle.font.color, excelThemes) : '',
                                        backgroundColor: cellStyle.fill && cellStyle.fill.color ? this._parseExcelColor(cellStyle.fill.color, excelThemes) : ''
                                    };
                                    if (cellStyle.font && fonts.indexOf(cellStyle.font.family) === -1) {
                                        fonts.push(cellStyle.font.family);
                                    }
                                }

                                // Get merged cell ranges.
                                if (item && wijmo.isNumber(item.rowSpan) && wijmo.isNumber(item.colSpan)) {
                                    for (var i = r; i < r + item.rowSpan; i++) {
                                        for (var j = c; j < c + item.colSpan; j++) {
                                            cellIndex = i * columnCount + j;
                                            mergedRange[cellIndex] = new _grid.CellRange(r, c, r + item.rowSpan - 1, c + item.colSpan - 1);
                                        }
                                    }
                                }
                            }
                        }

                        if (!groupCollapsed && row && !row.visible && row.visible != undefined) {
                            flex.rows[currentIncludeRowHeader ? r - 1 : r].visible = row.visible;
                        }
                    }

                    if (currentSheet.frozenPane) {
                        frozenColumns = currentSheet.frozenPane.columns;
                        if (wijmo.isNumber(frozenColumns) && !isNaN(frozenColumns)) {
                            flex.frozenColumns = frozenColumns;
                        }

                        frozenRows = currentSheet.frozenPane.rows;
                        if (wijmo.isNumber(frozenRows) && !isNaN(frozenRows)) {
                            flex.frozenRows = currentIncludeRowHeader && frozenRows > 0 ? frozenRows - 1 : frozenRows;
                        }
                    }

                    for (c = 0; c < flex.columnHeaders.columns.length; c++) {
                        columnSetting = columns[c];
                        column = flex.columns[c];
                        if (currentIncludeRowHeader) {
                            sheetHeader = sheetHeaders ? sheetHeaders.cells[c] : undefined;
                            if (sheetHeader && sheetHeader.value) {
                                headerForamt = this._parseExcelFormat(sheetHeader);
                                columnHeader = wijmo.Globalize.format(sheetHeader.value, headerForamt);
                            } else {
                                columnHeader = this._numAlpha(c);
                            }
                        } else {
                            columnHeader = this._numAlpha(c);
                        }
                        column.header = columnHeader;
                        if (columnSetting) {
                            if (columnSetting.dataType) {
                                column.dataType = columnSetting.dataType;
                            }
                            column.format = columnSetting.format;
                        }
                    }

                    // Set sheet related info for importing.
                    // This property contains the name, style of cells, merge cells and used fonts of current sheet.
                    if (moreSheets) {
                        flex['wj_sheetInfo'] = {
                            name: currentSheet.name,
                            styledCells: styledCells,
                            mergedRange: mergedRange,
                            fonts: fonts
                        };
                    }

                    if (sheetIndex === 0) {
                        flex['styledCells'] = styledCells;
                        flex['mergedRange'] = mergedRange;
                    }

                    if (moreSheets && sheetIndex > 0) {
                        moreSheets.push(flex);
                    }
                }
            };

            // export the flexgrid to excel file
            ExcelConverter._exportFlexGrid = function (flex, file, convertOption) {
                var worksheetData = [], columnSettings = [], workSheet = {
                    name: '',
                    cols: [],
                    rows: [],
                    frozenPane: {},
                    summaryBelow: false
                }, groupLevel = 0, worksheetDataHeader, rowHeight, column, row, groupRow, isGroupRow, value, columnSetting, ri, ci, sheetInfo;

                //fakeCell: HTMLDivElement,
                //headerCellStyle: any;
                // Set sheet name for the exporting sheet.
                sheetInfo = flex['wj_sheetInfo'];
                workSheet.name = sheetInfo ? sheetInfo.name : '';

                //if (!sheetInfo) {
                //	fakeCell = document.createElement('div'),
                //	fakeCell.style.visibility = 'hidden';
                //	flex.hostElement.appendChild(fakeCell);
                //	headerCellStyle = this._getCellStyle(flex, fakeCell, 0, 0, "columnHeaders") || {};
                //}
                // add the headers in the worksheet.
                if (convertOption.includeColumnHeader && flex.columnHeaders.rows.length > 0) {
                    for (ri = 0; ri < flex.columnHeaders.rows.length; ri++) {
                        worksheetDataHeader = {
                            visible: true,
                            cells: []
                        };
                        rowHeight = flex.columnHeaders.rows[ri].height;
                        if (rowHeight) {
                            rowHeight = rowHeight * 72 / 96;
                        }
                        worksheetDataHeader.height = rowHeight;
                        worksheetDataHeader.visible = true;

                        for (ci = 0; ci < flex.columnHeaders.columns.length; ci++) {
                            column = flex.columnHeaders.columns[ci];
                            value = flex.columnHeaders.getCellData(ri, ci, true);

                            if (ri === 0) {
                                columnSetting = this._getColumnSetting(column, flex.columnHeaders.columns.defaultSize);
                                columnSettings.push(columnSetting);
                            }

                            worksheetDataHeader.cells.push({
                                value: value,
                                //style: this._extend(this._parseCellStyle(headerCellStyle), {
                                //	font: {
                                //		bold: true
                                //	},
                                //	hAlign: columnSetting.style.hAlign
                                //})
                                style: {
                                    font: {
                                        bold: true
                                    },
                                    hAlign: columnSetting.style.hAlign
                                }
                            });
                        }
                        worksheetData.push(worksheetDataHeader);
                    }
                } else {
                    for (ci = 0; ci < flex.columnHeaders.columns.length; ci++) {
                        column = flex.columnHeaders.columns[ci];

                        columnSetting = this._getColumnSetting(column, flex.columnHeaders.columns.defaultSize);
                        columnSettings.push(columnSetting);
                    }
                }
                workSheet.cols = columnSettings;

                for (ri = 0; ri < flex.cells.rows.length; ri++) {
                    row = flex.rows[ri];
                    isGroupRow = row instanceof _grid.GroupRow;

                    if (isGroupRow) {
                        groupRow = wijmo.tryCast(row, _grid.GroupRow);
                        groupLevel = groupRow.level + 1;
                    }

                    worksheetData.push(this._parseFlexGridRowToSheetRow(flex, row, ri, columnSettings, convertOption.includeColumnHeader, isGroupRow, groupLevel));
                }

                workSheet.rows = worksheetData;
                workSheet.frozenPane = {
                    rows: convertOption.includeColumnHeader ? (flex.frozenRows + flex.columnHeaders.rows.length) : flex.frozenRows,
                    columns: flex.frozenColumns
                };

                file.sheets.push(workSheet);
                //if (!sheetInfo) {
                //	// done with style element
                //	flex.hostElement.removeChild(fakeCell);
                //}
            };

            // Parse the row data of flex grid to a sheet row
            ExcelConverter._parseFlexGridRowToSheetRow = function (flex, row, rowIndex, columnSettings, includeColumnHeader, isGroupRow, groupLevel) {
                var rowHeight = row.height, worksheetDataItem, columnSetting, format, val, unformattedVal, groupHeader, isFormula, cellIndex, cellStyle, mergedCells, rowSpan, colSpan, sheetInfo, isCommonRow;

                //fakeCell: HTMLDivElement;
                sheetInfo = flex['wj_sheetInfo'];

                worksheetDataItem = {
                    visible: true,
                    cells: []
                };

                if (rowHeight) {
                    rowHeight = rowHeight * 72 / 96;
                }
                worksheetDataItem.visible = row.visible;
                worksheetDataItem.height = rowHeight;
                worksheetDataItem.groupLevel = isGroupRow ? (groupLevel - 1) : groupLevel;

                isCommonRow = row.constructor === wijmo.grid.Row || row.constructor === wijmo.grid._NewRowTemplate;// s.h. || row.constructor === wijmo.grid.detail.DetailRow;

                for (var ci = 0; ci < flex.columnHeaders.columns.length; ci++) {
                    colSpan = 1;
                    rowSpan = 1;
                    if (sheetInfo) {
                        cellIndex = rowIndex * flex.columns.length + ci;

                        // Get merge range for cell.
                        if (sheetInfo.mergedRange) {
                            mergedCells = sheetInfo.mergedRange[cellIndex];
                        }

                        // Get style for cell.
                        if (sheetInfo.styledCells) {
                            cellStyle = sheetInfo.styledCells[cellIndex];
                        }
                    } else {
                        //cellStyle = this._getCellStyle(flex, fakeCell, rowIndex, ci) || {};
                    }

                    if (mergedCells) {
                        if (rowIndex === mergedCells.topRow && ci === mergedCells.leftCol) {
                            rowSpan = mergedCells.bottomRow - mergedCells.topRow + 1;
                            colSpan = mergedCells.rightCol - mergedCells.leftCol + 1;
                        } else {
                            continue;
                        }
                    }

                    columnSetting = columnSettings[ci];
                    if (isCommonRow || isGroupRow) {
                        val = flex.getCellData(rowIndex, ci, true);
                        unformattedVal = flex.getCellData(rowIndex, ci, false);
                        isFormula = val && wijmo.isString(val) && val.length > 1 && val[0] === '=';
                        format = columnSetting.style.format;
                        if (!format) {
                            if (wijmo.isDate(unformattedVal)) {
                                format = 'm/d/yyyy';
                            } else if (wijmo.isNumber(unformattedVal) && !wijmo.isInt(unformattedVal)) {
                                format = this._formatMap['n'];
                            } else if (wijmo.isInt(unformattedVal)) {
                                if (unformattedVal.toString() === val) {
                                    format = '#,##0';
                                } else {
                                    format = 'General';
                                }
                            }
                        }
                    } else {
                        val = flex.columnHeaders.getCellData(0, ci, true);
                        format = 'General';
                    }

                    if (isGroupRow && row['hasChildren'] && ci === flex.columns.firstVisibleIndex) {
                        // Process the group header of the flex grid.
                        if (val) {
                            groupHeader = val;
                        } else {
                            groupHeader = row.dataItem && row.dataItem.groupDescription ? row.dataItem.groupDescription.propertyName + ': ' + row.dataItem.name + ' (' + row.dataItem.items.length + ' items)' : '';
                        }

                        worksheetDataItem.cells.push({
                            value: groupHeader,
                            formula: isFormula ? this._parseExcelFormula(val) : null,
                            colSpan: colSpan,
                            rowSpan: rowSpan,
                            style: this._extend(this._parseCellStyle(cellStyle), {
                                formatCode: format,
                                originalFormat: columnSetting.style.format,
                                font: {
                                    bold: true
                                },
                                hAlign: 'left',
                                indent: groupLevel - 1
                            })
                        });
                    } else {
                        // Add the cell content
                        worksheetDataItem.cells.push({
                            value: format === 'General' ? val : unformattedVal,
                            formula: isFormula ? this._parseExcelFormula(val) : null,
                            colSpan: colSpan,
                            rowSpan: rowSpan,
                            style: this._extend(this._parseCellStyle(cellStyle), {
                                formatCode: format,
                                originalFormat: columnSetting.style.format,
                                hAlign: cellStyle && cellStyle.textAlign ? undefined : wijmo.isDate(unformattedVal) && columnSetting.style.hAlign === '' ? 'left' : columnSetting.style.hAlign
                            })
                        });
                    }
                }

                //if (!sheetInfo) {
                //	// done with style element
                //	flex.hostElement.removeChild(fakeCell);
                //}
                return worksheetDataItem;
            };

            // Parse css style to Excel style.
            // To jcc: you may merge your changes here.
            ExcelConverter._parseCellStyle = function (cellStyle) {
                var fontSize = cellStyle && cellStyle.fontSize ? +cellStyle.fontSize.substring(0, cellStyle.fontSize.indexOf('px')) : 14;

                // We should parse the font size from pixel to point for exporting.
                if (isNaN(fontSize)) {
                    fontSize = 10;
                } else {
                    fontSize = Math.round(fontSize * 72 / 96);
                }

                return {
                    font: {
                        bold: cellStyle && cellStyle.fontWeight && cellStyle.fontWeight === 'bold',
                        italic: cellStyle && cellStyle.fontStyle && cellStyle.fontStyle === 'italic',
                        underline: cellStyle && cellStyle.textDecoration && cellStyle.textDecoration === 'underline',
                        family: cellStyle ? this._parseExcelFontFamily(cellStyle.fontFamily) : 'Arial',
                        size: fontSize,
                        color: cellStyle && cellStyle.color ? cellStyle.color : ''
                    },
                    fill: {
                        color: cellStyle && cellStyle.backgroundColor ? cellStyle.backgroundColor : ''
                    },
                    hAlign: cellStyle && cellStyle.textAlign
                };
            };

            // Parse the cell format of flex grid to excel format.
            ExcelConverter._parseCellFormat = function (format) {
                var dec = -1, fisrtFormatChar = format[0], mapFormat = this._formatMap[fisrtFormatChar], currencySymbol = wijmo.culture.Globalize.numberFormat.currency.symbol, decimalArray = [], xlsxFormat;

                if (!mapFormat) {
                    return 'General';
                }

                if (fisrtFormatChar === 'c') {
                    mapFormat = mapFormat.replace(/\{0\}/g, currencySymbol);
                }

                if (format.length > 1) {
                    dec = parseInt(format.substr(1));
                    if (dec === 0) {
                        return mapFormat.replace(/\.00/g, '');
                    }
                }

                if (!isNaN(dec)) {
                    for (var i = 0; i < dec; i++) {
                        decimalArray.push(0);
                    }
                }

                if (decimalArray.length > 0) {
                    xlsxFormat = mapFormat.replace(/\.00/g, '.' + decimalArray.join(''));
                } else {
                    if (mapFormat) {
                        xlsxFormat = mapFormat;
                    } else {
                        xlsxFormat = format.replace(/tt/, 'AM/PM');
                    }
                }

                return xlsxFormat;
            };

            // parse the basic excel format to js format
            ExcelConverter._parseExcelFormat = function (item) {
                if (item === undefined || item === null || item.value === undefined || item.value === null || isNaN(item.value)) {
                    return undefined;
                }

                var formatCode = item.style && item.style.formatCode ? item.style.formatCode : '', currencySymbol = wijmo.culture.Globalize.numberFormat.currency.symbol, format = '', lastDotIndex;

                if (wijmo.isDate(item.value)) {
                    if (!formatCode || formatCode === 'General') {
                        return 'M/d/yyyy';
                    }
                    format = formatCode.replace(/&quot;/g, '').replace(/;@/g, '').replace(/\[\$\-.+\]/g, '').replace(/AM\/PM/gi, 'tt').replace(/\\[\-\s,]/g, function (str) {
                        return str.substring(1);
                    }).replace(/m+:?|:?m+/g, function (str) {
                        if (str.indexOf(':') > -1) {
                            return str;
                        } else {
                            return str.toUpperCase();
                        }
                    });
                } else if (wijmo.isNumber(item.value)) {
                    if (!formatCode || formatCode === 'General') {
                        return item.value === Math.round(item.value) ? 'd' : 'f2';
                    }
                    lastDotIndex = formatCode.lastIndexOf('.');
                    if (formatCode.indexOf(currencySymbol) > -1 || formatCode.indexOf('$') > -1) {
                        format = 'c';
                    } else if (formatCode[formatCode.length - 1] === '%') {
                        format = 'p';
                    } else {
                        format = 'n';
                    }

                    if (lastDotIndex > -1) {
                        format += formatCode.substring(lastDotIndex, formatCode.lastIndexOf('0')).length;
                    } else {
                        format += '0';
                    }
                } else {
                    format = formatCode;
                }

                return format;
            };

            // Parse the css font family to excel font family.
            ExcelConverter._parseExcelFontFamily = function (fontFamily) {
                var firstQuotesIndex, lastQuotesIndex;

                if (fontFamily) {
                    firstQuotesIndex = fontFamily.indexOf('"');
                    lastQuotesIndex = fontFamily.lastIndexOf('"');

                    if (firstQuotesIndex > -1 && firstQuotesIndex !== lastQuotesIndex) {
                        fontFamily = fontFamily.substring(firstQuotesIndex + 1, lastQuotesIndex);
                    }
                } else {
                    fontFamily = 'Arial';
                }
                return fontFamily;
            };

            // Parse the excel color to css color.
            ExcelConverter._parseExcelColor = function (color, excelThemes) {
                var parsedColor = '';

                if (!color) {
                    return parsedColor;
                }

                if (typeof color === "string") {
                    return color;
                }

                switch (color.colorType) {
                    case 'rgbColor':
                        parsedColor = '#' + color.value.substring(2);
                        break;
                    case 'indexedColor':
                        parsedColor = '#' + this._indexedColors[color.value];
                        break;
                    case 'themeColor':
                        parsedColor = this._getColorFromExcelTheme(excelThemes[color.theme], color.value);
                        break;
                }

                return parsedColor;
            };

            // Get the color by the excel theme color and value.
            ExcelConverter._getColorFromExcelTheme = function (themeColor, value) {
                var numberVal, color, hslArray;

                if (value) {
                    numberVal = +value;
                    color = new wijmo.Color('#' + themeColor), hslArray = color.getHsl();

                    // About the tint value and theme color convert to rgb color,
                    // please refer https://msdn.microsoft.com/en-us/library/documentformat.openxml.spreadsheet.tabcolor.aspx
                    if (numberVal < 0) {
                        hslArray[2] = hslArray[2] * (1.0 + numberVal);
                    } else {
                        hslArray[2] = hslArray[2] * (1.0 - numberVal) + (1 - 1 * (1.0 - numberVal));
                    }

                    color = wijmo.Color.fromHsl(hslArray[0], hslArray[1], hslArray[2]);
                    return color.toString();
                }

                // if the color value is undefined, we should return the themeColor (TFS 121712)
                return '#' + themeColor;
            };

            // Parse the formula to excel formula.
            ExcelConverter._parseExcelFormula = function (formula) {
                var func = formula.substring(1, formula.indexOf('(')).toLowerCase(), format;

                switch (func) {
                    case 'ceiling':
                    case 'floor':
                        formula = formula.substring(0, formula.lastIndexOf(')')) + ', 1)';
                        break;
                    case 'round':
                        formula = formula.substring(0, formula.lastIndexOf(')')) + ', 0)';
                        break;
                    case 'text':
                        format = formula.substring(formula.lastIndexOf(','), formula.lastIndexOf('\"'));
                        format = this._parseCellFormat(format.substring(format.lastIndexOf('\"') + 1));
                        formula = formula.substring(0, formula.lastIndexOf(',') + 1) + '\"' + format + '\")';
                        break;
                }
                return formula;
            };

            // Parse the excel formula to flexsheet formula.
            ExcelConverter._parseFlexSheetFormula = function (excelFormula) {
                var func = excelFormula.substring(1, excelFormula.indexOf('(')).toLowerCase(), value, format, lowerCaseFormat;

                switch (func) {
                    case 'ceiling':
                    case 'floor':
                    case 'round':
                        excelFormula = excelFormula.substring(0, excelFormula.lastIndexOf(',')) + ')';
                        break;
                    case 'text':
                        format = excelFormula.substring(excelFormula.indexOf('\"'), excelFormula.lastIndexOf('\"'));

                        // Fix TFS issue 122648
                        format = format.substring(format.lastIndexOf('\"') + 1);
                        lowerCaseFormat = format.toLowerCase();
                        if (lowerCaseFormat.indexOf('0') > -1) {
                            // For processing number format
                            value = 0;
                        } else if (lowerCaseFormat.indexOf('m') > -1) {
                            // For processing date format
                            value = new Date();
                        } else {
                            // For processing string format
                            value = '';
                        }
                        format = this._parseExcelFormat({
                            value: value,
                            style: {
                                formatCode: format
                            }
                        });
                        format = format.replace(/Y+/g, function (str) {
                            return str.toLowerCase();
                        });
                        excelFormula = excelFormula.substring(0, excelFormula.indexOf('\"') + 1) + format + '\")';
                        break;
                }
                return excelFormula;
            };

            // Gets the column setting, include width, visible, format and alignment
            ExcelConverter._getColumnSetting = function (column, defaultWidth) {
                var width = column.size;

                width = width ? width / 6 : defaultWidth / 6;

                return {
                    autoWidth: true,
                    width: width,
                    visible: column.visible,
                    style: {
                        format: column.format ? this._parseCellFormat(column.format) : '',
                        hAlign: this._toExcelHAlign(column.getAlignment())
                    }
                };
            };

            // Parse the CSS alignment to excel hAlign.
            ExcelConverter._toExcelHAlign = function (value) {
                value = value ? value.trim().toLowerCase() : value;
                if (!value)
                    return value;

                if (value.indexOf('center') > -1) {
                    return 'center';
                }

                if (value.indexOf('right') > -1 || value.indexOf('end') > -1) {
                    return 'right';
                }

                if (value.indexOf('justify') > -1) {
                    return 'justify';
                }

                return 'left';
            };

            // gets column count for specific row
            ExcelConverter._getColumnCount = function (sheetData) {
                var columnCount = 0, data;

                for (var i = 0; i < sheetData.length; i++) {
                    data = sheetData[i] ? sheetData[i].cells : [];
                    if (data && data.length > columnCount) {
                        columnCount = data.length;
                    }
                }

                return columnCount;
            };

            // convert the column index to alphabet
            ExcelConverter._numAlpha = function (i) {
                var t = Math.floor(i / 26) - 1;
                return (t > -1 ? this._numAlpha(t) : '') + this._alphabet.charAt(i % 26);
            };

            // Get DataType for value of the specific excel item
            ExcelConverter._getItemType = function (item) {
                if (item === undefined || item === null || item.value === undefined || item.value === null || isNaN(item.value)) {
                    return undefined;
                }

                return wijmo.getType(item.value);
            };

            // Set column definition for the Flex Grid
            ExcelConverter._setColumn = function (columns, columnIndex, item) {
                var dataType, format;
                if (!columns[columnIndex]) {
                    columns.push({
                        dataType: this._getItemType(item),
                        format: this._parseExcelFormat(item)
                    });
                } else {
                    dataType = this._getItemType(item);
                    if (columns[columnIndex].dataType === undefined || (dataType !== undefined && dataType !== 1 /* String */ && columns[columnIndex].dataType === 1 /* String */)) {
                        columns[columnIndex].dataType = dataType;
                    }

                    format = this._parseExcelFormat(item);
                    if (format && format !== 'General') {
                        columns[columnIndex].format = format;
                    }
                }
            };

            // Get value from the excel cell item
            ExcelConverter._getItemValue = function (item) {
                if (item === undefined || item === null || item.value === undefined || item.value === null) {
                    return undefined;
                }

                var val = item.value;

                if (wijmo.isNumber(val) && isNaN(val)) {
                    return '';
                } else if (val instanceof Date && isNaN(val.getTime())) {
                    return '';
                } else {
                    return val;
                }
            };

            // Get style of cell.
            //private static _getCellStyle(flex: FlexGrid, fakeCell: HTMLDivElement, r: number, c: number, panel: string = "cells"): any {
            //	// create element to get styles
            //	var theStyle: CSSStyleDeclaration;
            //	try {
            //		// get styles for any panel, row, column
            //		flex.cellFactory.updateCell(flex[panel], r, c, fakeCell);
            //	} catch (ex) {
            //		return undefined;
            //	}
            //	theStyle = window.getComputedStyle(fakeCell);
            //	return theStyle;
            //}
            // extends the source hash to destination hash
            ExcelConverter._extend = function (dst, src) {
                for (var key in src) {
                    var value = src[key];
                    if (wijmo.isObject(value) && dst[key]) {
                        wijmo.copy(dst[key], value); // copy sub-objects
                    } else {
                        dst[key] = value; // assign values
                    }
                }
                return dst;
            };

            // taken from: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Base64_encoding_and_decoding#The_.22Unicode_Problem.22
            ExcelConverter._b64ToUint6 = function (nChr) {
                return nChr > 64 && nChr < 91 ? nChr - 65 : nChr > 96 && nChr < 123 ? nChr - 71 : nChr > 47 && nChr < 58 ? nChr + 4 : nChr === 43 ? 62 : nChr === 47 ? 63 : 0;
            };

            // decode the base64 string to int array
            ExcelConverter._base64DecToArr = function (sBase64, nBlocksSize) {
                var sB64Enc = sBase64.replace(/[^A-Za-z0-9\+\/]/g, ""), nInLen = sB64Enc.length, nOutLen = nBlocksSize ? Math.ceil((nInLen * 3 + 1 >> 2) / nBlocksSize) * nBlocksSize : nInLen * 3 + 1 >> 2, taBytes = new Uint8Array(nOutLen);

                for (var nMod3, nMod4, nUint24 = 0, nOutIdx = 0, nInIdx = 0; nInIdx < nInLen; nInIdx++) {
                    nMod4 = nInIdx & 3;
                    nUint24 |= this._b64ToUint6(sB64Enc.charCodeAt(nInIdx)) << 18 - 6 * nMod4;
                    if (nMod4 === 3 || nInLen - nInIdx === 1) {
                        for (nMod3 = 0; nMod3 < 3 && nOutIdx < nOutLen; nMod3++, nOutIdx++) {
                            taBytes[nOutIdx] = nUint24 >>> (16 >>> nMod3 & 24) & 255;
                        }
                        nUint24 = 0;
                    }
                }
                return taBytes;
            };

            // taken from https://developer.mozilla.org/en-US/docs/Web/API/WindowBase64/Base64_encoding_and_decoding
            /* Base64 string to array encoding */
            ExcelConverter._uint6ToB64 = function (nUint6) {
                return nUint6 < 26 ? nUint6 + 65 : nUint6 < 52 ? nUint6 + 71 : nUint6 < 62 ? nUint6 - 4 : nUint6 === 62 ? 43 : nUint6 === 63 ? 47 : 65;
            };

            ExcelConverter._base64EncArr = function (aBytes) {
                var nMod3 = 2, sB64Enc = "";

                for (var nLen = aBytes.length, nUint24 = 0, nIdx = 0; nIdx < nLen; nIdx++) {
                    nMod3 = nIdx % 3;
                    if (nIdx > 0 && (nIdx * 4 / 3) % 76 === 0) {
                        sB64Enc += "\r\n";
                    }
                    nUint24 |= aBytes[nIdx] << (16 >>> nMod3 & 24);
                    if (nMod3 === 2 || aBytes.length - nIdx === 1) {
                        sB64Enc += String.fromCharCode(this._uint6ToB64(nUint24 >>> 18 & 63), this._uint6ToB64(nUint24 >>> 12 & 63), this._uint6ToB64(nUint24 >>> 6 & 63), this._uint6ToB64(nUint24 & 63));
                        nUint24 = 0;
                    }
                }

                return sB64Enc.substr(0, sB64Enc.length - 2 + nMod3) + (nMod3 === 2 ? '' : nMod3 === 1 ? '=' : '==');
            };
            ExcelConverter._formatMap = {
                n: '#,##0.00',
                c: '{0}#,##0.00_);({0}#,##0.00)',
                p: '0.00%',
                f: '###0.00'
            };
            ExcelConverter._alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

            ExcelConverter._indexedColors = [
                '000000', 'FFFFFF', 'FF0000', '00FF00', '0000FF', 'FFFF00', 'FF00FF', '00FFFF',
                '000000', 'FFFFFF', 'FF0000', '00FF00', '0000FF', 'FFFF00', 'FF00FF', '00FFFF',
                '800000', '008000', '000080', '808000', '800080', '008080', 'C0C0C0', '808080',
                '9999FF', '993366', 'FFFFCC', 'CCFFFF', '660066', 'FF8080', '0066CC', 'CCCCFF',
                '000080', 'FF00FF', 'FFFF00', '00FFFF', '800080', '800000', '008080', '0000FF',
                '00CCFF', 'CCFFFF', 'CCFFCC', 'FFFF99', '99CCFF', 'FF99CC', 'CC99FF', 'FFCC99',
                '3366FF', '33CCCC', '99CC00', 'FFCC00', 'FF9900', 'FF6600', '666699', '969696',
                '003366', '339966', '003300', '333300', '993300', '993366', '333399', '333333',
                '000000', 'FFFFFF'];
            return ExcelConverter;
        })();
        _grid.ExcelConverter = ExcelConverter;
    })(wijmo.grid || (wijmo.grid = {}));
    var grid = wijmo.grid;
})(wijmo || (wijmo = {}));





