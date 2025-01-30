/*
Example:
var dataTable = initDataTables('table-1', 'loader-category', 'card-category', 'new-record-button', false,
    'News', "{{ route('admin.category.data') }}",
    [{
            data: "name",
            name: "name",
            className: "align-middle",
        },
        {
            data: "description",
            name: "description",
            className: "align-middle",
        },
        {
            data: "action",
            name: "action",
            className: "align-middle",
            searchable: false,
            orderable: false,
        },
    ]
);
*/

function initDataTables(
    tableId,
    loaderId,
    cardId,
    newRecordId,
    isResponsive,
    title,
    url,
    columns,
    params = false,
    saveState = false
) {
    var initColumns = [];
    if (isResponsive) {
        initColumns.push({
            // For Responsive
            className: "control",
            orderable: false,
            searchable: false,
            data: "id",
            render: function (data, type, full, meta) {
                return "";
            },
        });

        $(`#${tableId} thead tr`).prepend("<th></th>");
    }

    initColumns.push({
        data: "id",
        render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        },
    });

    let dataTable = $("#" + tableId).DataTable({
        autoWidth: true,
        processing: true,
        serverSide: true,
        search: {
            return: true,
        },
        stateSave:  saveState,
        ajax: {
            url: url,
            method: 'GET',
            data: function (d) {
                if (params) {
                    params.forEach(variable => {
                        d[variable] = $(`#${variable}`).val(); // Menggunakan template literal untuk memilih elemen
                    });
                }
            },
            beforeSend: function () {
                // var loader = `@include('components.loader', ['idLoader' => ${loaderId}])`;
                // $("#" + loaderId).append(loader);
                var loader = `
                    <div class="loader-overlay" id="${loaderId}">
                    <div class="sk-fold sk-primary">
                        <div class="sk-fold-cube"></div>
                        <div class="sk-fold-cube"></div>
                        <div class="sk-fold-cube"></div>
                        <div class="sk-fold-cube"></div>
                    </div>
                    <h5>LOADING...</h5>
                </div>
                `;
                $("#" + cardId).append(loader);
            },
            complete: function () {
                $("#" + loaderId).remove();
            },
        },
        columns: [...initColumns, ...columns],
        order: [[isResponsive ? 1 : 0, "desc"]],
        dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-6 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-n6 mt-md-0"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        displayLength: 10,
        lengthMenu: [5, 10, 25, 50, 75, 100, "All"],
        language: {
            paginate: {
                next: '<i class="ti ti-chevron-right ti-sm"></i>',
                previous: '<i class="ti ti-chevron-left ti-sm"></i>',
            },
        },
        buttons: [
            {
                extend: "collection",
                className:
                    "btn btn-label-primary dropdown-toggle me-4 waves-effect waves-light border-none",
                text: '<i class="ti ti-file-export ti-xs me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                buttons: [
                    {
                        extend: "print",
                        text: '<i class="ti ti-printer me-1" ></i>Print',
                        className: "dropdown-item",
                        exportOptions: {
                            columns: function (idx, data, node) {
                                // Kembalikan `true` untuk mengekspor semua kolom
                                return true;
                            },
                            // prevent avatar to be display
                            format: {
                                body: function (inner, coldex, rowdex) {
                                    if (inner.length <= 0) return inner;
                                    var el = $.parseHTML(inner);
                                    var result = "";
                                    $.each(el, function (index, item) {
                                        if (
                                            item.classList !== undefined &&
                                            item.classList.contains("user-name")
                                        ) {
                                            result =
                                                result +
                                                item.lastChild.firstChild
                                                    .textContent;
                                        } else if (
                                            item.innerText === undefined
                                        ) {
                                            result = result + item.textContent;
                                        } else result = result + item.innerText;
                                    });
                                    return result;
                                },
                            },
                        },
                        customize: function (win) {
                            //customize print view for dark
                            $(win.document.body)
                                .css("color", config.colors.headingColor)
                                .css("border-color", config.colors.borderColor)
                                .css("background-color", config.colors.bodyBg);
                            $(win.document.body)
                                .find("table")
                                .addClass("compact")
                                .css("color", "inherit")
                                .css("border-color", "inherit")
                                .css("background-color", "inherit");
                        },
                    },
                    {
                        extend: "csv",
                        text: '<i class="ti ti-file-text me-1" ></i>Csv',
                        className: "dropdown-item",
                        exportOptions: {
                            columns: function (idx, data, node) {
                                // Kembalikan `true` untuk mengekspor semua kolom
                                return true;
                            },
                            // prevent avatar to be display
                            format: {
                                body: function (inner, coldex, rowdex) {
                                    if (inner.length <= 0) return inner;
                                    var el = $.parseHTML(inner);
                                    var result = "";
                                    $.each(el, function (index, item) {
                                        if (
                                            item.classList !== undefined &&
                                            item.classList.contains("user-name")
                                        ) {
                                            result =
                                                result +
                                                item.lastChild.firstChild
                                                    .textContent;
                                        } else if (
                                            item.innerText === undefined
                                        ) {
                                            result = result + item.textContent;
                                        } else result = result + item.innerText;
                                    });
                                    return result;
                                },
                            },
                        },
                    },
                    {
                        extend: "excel",
                        text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel',
                        className: "dropdown-item",
                        exportOptions: {
                            columns: function (idx, data, node) {
                                // Kembalikan `true` untuk mengekspor semua kolom
                                return true;
                            },
                            // prevent avatar to be display
                            format: {
                                body: function (inner, coldex, rowdex) {
                                    if (inner.length <= 0) return inner;
                                    var el = $.parseHTML(inner);
                                    var result = "";
                                    $.each(el, function (index, item) {
                                        if (
                                            item.classList !== undefined &&
                                            item.classList.contains("user-name")
                                        ) {
                                            result =
                                                result +
                                                item.lastChild.firstChild
                                                    .textContent;
                                        } else if (
                                            item.innerText === undefined
                                        ) {
                                            result = result + item.textContent;
                                        } else result = result + item.innerText;
                                    });
                                    return result;
                                },
                            },
                        },
                    },
                    {
                        extend: "pdf",
                        text: '<i class="ti ti-file-description me-1"></i>Pdf',
                        className: "dropdown-item",
                        exportOptions: {
                            columns: function (idx, data, node) {
                                // Kembalikan `true` untuk mengekspor semua kolom
                                return true;
                            },
                            // prevent avatar to be display
                            format: {
                                body: function (inner, coldex, rowdex) {
                                    if (inner.length <= 0) return inner;
                                    var el = $.parseHTML(inner);
                                    var result = "";
                                    $.each(el, function (index, item) {
                                        if (
                                            item.classList !== undefined &&
                                            item.classList.contains("user-name")
                                        ) {
                                            result =
                                                result +
                                                item.lastChild.firstChild
                                                    .textContent;
                                        } else if (
                                            item.innerText === undefined
                                        ) {
                                            result = result + item.textContent;
                                        } else result = result + item.innerText;
                                    });
                                    return result;
                                },
                            },
                        },
                    },
                    {
                        extend: "copy",
                        text: '<i class="ti ti-copy me-1" ></i>Copy',
                        className: "dropdown-item",
                        exportOptions: {
                            columns: function (idx, data, node) {
                                // Kembalikan `true` untuk mengekspor semua kolom
                                return true;
                            },
                            // prevent avatar to be display
                            format: {
                                body: function (inner, coldex, rowdex) {
                                    if (inner.length <= 0) return inner;
                                    var el = $.parseHTML(inner);
                                    var result = "";
                                    $.each(el, function (index, item) {
                                        if (
                                            item.classList !== undefined &&
                                            item.classList.contains("user-name")
                                        ) {
                                            result =
                                                result +
                                                item.lastChild.firstChild
                                                    .textContent;
                                        } else if (
                                            item.innerText === undefined
                                        ) {
                                            result = result + item.textContent;
                                        } else result = result + item.innerText;
                                    });
                                    return result;
                                },
                            },
                        },
                    },
                ],
            },
            {
                attr: {
                    id: newRecordId,
                },
                text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Record</span>',
                className:
                    "create-new btn btn-primary waves-effect waves-light",
            },
        ],
        responsive: isResponsive
            ? {
                  details: {
                      display: $.fn.dataTable.Responsive.display.modal({
                          header: function (row) {
                              var data = row.data();
                              return "Details of " + data["name"];
                          },
                      }),
                      type: "column",
                      renderer: function (api, rowIdx, columns) {
                          var data = $.map(columns, function (col, i) {
                              return col.title !== "" // ? Do not show row in modal popup if title is blank (for check box)
                                  ? '<tr data-dt-row="' +
                                        col.rowIndex +
                                        '" data-dt-column="' +
                                        col.columnIndex +
                                        '">' +
                                        "<td>" +
                                        col.title +
                                        ":" +
                                        "</td> " +
                                        "<td>" +
                                        col.data +
                                        "</td>" +
                                        "</tr>"
                                  : "";
                          }).join("");

                          return data
                              ? $('<table class="table"/><tbody />').append(
                                    data
                                )
                              : false;
                      },
                  },
              }
            : false,
        initComplete: function (settings, json) {
            $(".card-header").after('<hr class="my-0">');
        },
    });

    $("div.head-label").html('<h5 class="card-title mb-0">' + title + "</h5>");
    $("div.dataTables_filter input", dataTable.table().container()).focus();
    return dataTable;
}
