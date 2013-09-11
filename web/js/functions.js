
/**
 * Function to setup and manage the calendar
 */
function setupCalendar(){
    $("#datepicker").datepicker({
        // Options to use spanish configuration
        //monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
        //dayNamesMin: [ "Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab" ],
        //firstDay: 1,
        //dateFormat: 'dd/mm/yy', <- Not working, produces error ¿?
        beforeShowDay: function(date) {
            var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#startDate").val());
            var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#endDate").val());

            return [true, date1 && ((date.getTime() == date1.getTime()) || (date2 && date >= date1 && date <= date2)) ? "dp-highlight" : ""];
        },

        onSelect: function(dateText, inst) {

            var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#startDate").val());
            var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#endDate").val());

            if (!date1 || date2) {
                $("#startDate").val(dateText);
                $("#endDate").val("");
                $(this).datepicker("option", "minDate", dateText);
            } else {
                $("#endDate").val(dateText);
                $(this).datepicker("option", "minDate", null);

                // reload data and show page 1
                gotoPage(1);
            }
        }
    });
}

/**
 * Function to iterate over pages
 * @param pageNumber
 */
function gotoPage(pageNumber){
    $("#productListTable").fadeOut("slow");

    // Country selected
    var selectedCountry = $("#countrySelect").val();

    // Pagesize
    var recordsSize = $("#numRecorsSelect").val();

    // Dates
    var startDate = $("#startDate").val();
    var endDate = $("#endDate").val();

    // path to ajax call
    var path = $("#path").val();
    $.post( path,
        {selectedCountry: selectedCountry,
            startDate : startDate,
            endDate : endDate,
            pagesize : recordsSize,
            nextPage:pageNumber},

        function(response){
            var datesHtml = response.startDate + " - " + response.endDate;

            $("#dates").html(datesHtml);

            // repaint the table
            repaintTable(response);

            // repaint the paginator
            repaintPaginator(response.gridPaginationData.currentPage,
                response.gridPaginationData.totalPages,
                response.gridPaginationData.totalRecords);
        }, "json");
}

/**
 * function to resize de list
 */
function resizeList(){
    $("#productListTable").fadeOut("slow");

    var recordsSize = $("#numRecorsSelect").val();
    var selectedCountry = $("#countrySelect").val();

    // Dates
    var startDate = $("#startDate").val();
    var endDate = $("#endDate").val();

    // path to ajax call
    var path = $("#path").val();

    $.post(path,
    {selectedCountry: selectedCountry,
        startDate : startDate,
        endDate : endDate,
        pagesize : recordsSize,
        nextPage:1},

        function(response){
            // repaint the table
            repaintTable(response);

            // repaint the paginator
            repaintPaginator(response.gridPaginationData.currentPage,
                response.gridPaginationData.totalPages,
                response.gridPaginationData.totalRecords);
        }, "json");
}

/**
 * Function to navigate directly to a concrete page
 */
function moveToPage(){
    var page = $('#selectPage').val();
    var totalPages = $('#totalPagesHidden').val();

    if (isNaN(parseInt(page))){
        alert('Please, provide a correct number page to jump');
    } else{
        if (parseInt(page) > totalPages){
            alert('Page out of range');
        } else{
            gotoPage(page)
        }
    }

}

/**
 * Function to repaint table of results
 * @param response
 */
function repaintTable(response){
    // Construct table of results
    var table = '<tr class="info">' +
        '<th>Product</th>' +
        '<th>Units sold</th>' +
        '<th>Total Cost</th>' +
        '<th>Total Revenue</th>' +
        '<th>Total Profit</th>' +
        '</tr>';

    for(cont = 0;cont < response.buy.length; cont++){
        table = table +'<tr>';
        table = table + '<td>' + response.buy[cont].product + '</td>';
        table = table + '<td>' + response.buy[cont].units + '</td>';

        var units =  response.buy[cont].units;
        var cost = Math.round((units * response.buy[cont].cost) * 100) / 100;
        var price =  Math.round((units * response.buy[cont].price) * 100) / 100;
        var profit = Math.round( (price - cost) * 100) / 100;

        table = table + '<td>' + cost + '</td>';
        table = table + '<td>' + price + '</td>';

        if (profit < 0){
            table = table + '<td class="negative_profit">' + profit + '</td>';
        } else{
            table = table + '<td>' + profit + '</td>';
        }


        table = table + '</tr>';
    }

    $("#productListTable").html(table);
    $("#productListTable").fadeIn("slow");

}

/**
 * Function to repaint paginator bar
 */
function repaintPaginator(currentPage, totalPages,totalRecords){
    currPage = parseInt(currentPage);
    totPage = parseInt(totalPages);

    $('#totalPagesHidden').val(totPage);

    $('#totalPagesLabel').html('Total pages : ' + totPage);
    $('#totalResultsLabel').html('Total results : ' + totalRecords);


    var paginator = '<ul>';

    if (currPage == 1){
        // ***** first page *****//

        // links for first, previous and page one
        paginator = paginator + '<li class="disabled"><a>&laquo;</a></li>';
        paginator = paginator + '<li class="disabled"><a>&lt;</a></li>';
        paginator = paginator + '<li class="active"><a>1</a></li>';

        // check de maximum link to paint
        var maxPageNumber = (currPage + 5);
        if (totPage <= 6){
            maxPageNumber = totPage;
        }

        // generate html for links to pages
        for(var contPage = currPage + 1; contPage<= maxPageNumber ;contPage++){
            paginator = paginator + '<li class="inactive">' +
                '<a onclick=gotoPage(' + contPage +')>' + contPage + '</a>' +
                '</li>';
        }

        // only paint '...' when have more than 6 pages
        if (totPage > 6){
            paginator = paginator + '<li class="disabled"><a>...</a></li>';
        }

        // links for next and last pages
        if (totPage == 1){
            paginator = paginator + '<li class="disabled"><a>&gt;</a></li>';
            paginator = paginator + '<li class="disabled"><a>&raquo;</a></li>';
        } else{
            paginator = paginator + '<li class="inactive">' +
                '<a onclick=gotoPage(' + (currPage + 1) +')>&gt;</a>' +
                '</li>';
            paginator = paginator + '<li class="inactive">' +
                '<a onclick=gotoPage(' + totPage +')>&raquo;</a>' +
                '</li>';
        }


    } else if (currPage == totPage){
        // ***** last page *****//

        // links for first, previous and '...'
        paginator = paginator + '<li class="inactive">' +
            '<a onclick=gotoPage(1)>&laquo;</a>' +
            '</li>';
        paginator = paginator + '<li class="inactive">' +
            '<a onclick=gotoPage(' + (currPage - 1) +')>&lt;</a>' +
            '</li>';

        var minPageNumber = (currPage - 5);
        if (totPage <= 6){
            minPageNumber = 1;
        }

        // only '...' for more than 6 pages
        if (totPage > 6){
            paginator = paginator + '<li class="disabled"><a>...</a></li>';
        }

        // generate links for last five pages
        for(var contPage = minPageNumber; contPage<=totPage;contPage++){
            if (contPage == currPage){
                paginator = paginator + '<li class="active">';
            } else{
                paginator = paginator + '<li class="inactive">';
            }

            paginator = paginator +
                '<a onclick=gotoPage(' + contPage +')>' + contPage + '</a>' +
                '</li>';
        }

        // links for last and next
        paginator = paginator + '<li class="disabled"><a>&gt;</a></li>';
        paginator = paginator + '<li class="disabled"><a>&raquo;</a></li>';
    } else{
        // ***** any page *****//

        // links for first and previous
        paginator = paginator + '<li class="inactive">' +
            '<a onclick=gotoPage(1)>&laquo;</a>' +
            '</li>';
        paginator = paginator + '<li class="inactive">' +
            '<a onclick=gotoPage(' + (currPage - 1) +')>&lt;</a>' +
            '</li>';


        // if thera are less than 6 pages, paint all the links without ...
        if (totPage <= 6){

            for(var contPage = 1; contPage<=totPage;contPage++){
                if (contPage == currPage){
                    paginator = paginator + '<li class="active">';
                } else{
                    paginator = paginator + '<li class="inactive">';
                }

                paginator = paginator +
                    '<a onclick=gotoPage(' + contPage +')>' + contPage + '</a>' +
                    '</li>';
            }
        } else{
            if ((currPage - 5) <= 1){
                for(var contPage = 1; contPage<=6;contPage++){
                    if (contPage == currPage){
                        paginator = paginator + '<li class="active">';
                    } else{
                        paginator = paginator + '<li class="inactive">';
                    }

                    paginator = paginator +
                        '<a onclick=gotoPage(' + contPage +')>' + contPage + '</a>' +
                        '</li>';
                }
                paginator = paginator + '<li class="disabled"><a>...</a></li>';
            } else if ((currPage + 5) >= totPage){
                paginator = paginator + '<li class="disabled"><a>...</a></li>';
                for(var contPage = (totPage-5); contPage<=totPage;contPage++){
                    if (contPage == currPage){
                        paginator = paginator + '<li class="active">';
                    } else{
                        paginator = paginator + '<li class="inactive">';
                    }

                    paginator = paginator +
                        '<a onclick=gotoPage(' + contPage +')>' + contPage + '</a>' +
                        '</li>';
                }
            } else{
                paginator = paginator + '<li class="disabled"><a>...</a></li>';
                for(var contPage = currPage-3; contPage<=(currPage + 3);contPage++){
                    // active class for first link
                    if (contPage == currPage){
                        paginator = paginator + '<li class="active">';
                    } else{
                        paginator = paginator + '<li class="inactive">';
                    }

                    paginator = paginator + '<a onclick=gotoPage(' + contPage +')>' + contPage + '</a>' +
                        '</li>';
                }
                paginator = paginator + '<li class="disabled"><a>...</a></li>';
            }
        }

        // links for last and next
        paginator = paginator + '<li class="inactive">' +
            '<a onclick=gotoPage(' + (currPage + 1) +')>&gt;</a>' +
            '</li>';
        paginator = paginator + '<li class="inactive">' +
            '<a onclick=gotoPage(' + totPage +')>&raquo;</a>' +
            '</li>';
    }

    paginator = paginator + "</ul>";

    $("#paginator").html(paginator);
}