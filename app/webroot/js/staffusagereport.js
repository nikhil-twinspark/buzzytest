$(function() {
    function exportTableToCSV($table, filename) {
        var $rows = $table.find('tr:has(td)'),
                // Temporary delimiter characters unlikely to be typed by keyboard
                // This is to avoid accidentally splitting the actual contents
                tmpColDelim = String.fromCharCode(11), // vertical tab character
                tmpRowDelim = String.fromCharCode(0), // null character

                // actual delimiter characters for CSV format
                colDelim = '","',
                rowDelim = '"\r\n"',
                // Grab text from table into CSV formatted string
                csv = '"' + $rows.map(function(i, row) {
                    var $row = $(row),
                            $cols = $row.find('td');

                    var rowspan = null;
                    return $cols.map(function(j, col) {
                        var $col = $(col),
                                text = $col.text();
                        if ($col.attr('rowspan') > 0) {
                            rowspan = $col.attr('rowspan');
                        }
                        return text.replace(/"/g, '""'); // escape double quotes

                    }).get().join(tmpColDelim);

                }).get().join(tmpRowDelim)
                .split(tmpRowDelim).join(rowDelim)
                .split(tmpColDelim).join(colDelim) + '"',
                // Data URI
                csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

        $(this)
                .attr({
                    'download': filename,
                    'href': csvData,
                    'target': '_blank'
                });
    }

    // This must be a hyperlink
    $(".weekly,.overall").on('click', function(event) {
        // CSV
        if ($(this).hasClass('weekly')) {
            exportTableToCSV.apply(this, [$('#report_weekly_table'), 'StaffUsageReportWeekly.csv']);
        } else {
            exportTableToCSV.apply(this, [$('#report_table'), 'StaffUsageReportOverall.csv']);
        }

        // IF CSV, don't do event.preventDefault() or return false
        // We actually need this to be a typical hyperlink
    });



    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        console.log(moment().subtract(29, 'days'));
    }
    cb(moment().subtract(29, 'days'), moment());

    var newdate = new Date();

    newdate.setDate(newdate.getDate() - 29); // minus the date
    var startDate = new Date(newdate);

    $('#reportrange').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }, "startDate": startDate,
        "endDate": new Date(), maxDate: new Date()
    }, cb);

    /* $('input[name="date_range_picker"]').daterangepicker({
     
     
     format: 'YYYY-MM-DD',
     maxDate:new Date(),
     });*/
    $('#asgnlink').on('click', function() {
        if (!$('#date_range_picker').text()) {
            alert('PLease select date range.');
            return false;
        }
        var reportType = 'sur';
        $.ajax({
            type: 'POST',
            url: '/StaffUsageReport/getreport',
            dataType: 'json',
            beforeSend: function() {
                $('#patient-loading-div').show();
            },
            data: {
                date_range: $('#date_range_picker').text(),
                type: reportType
            },
            success: function(r) {
                $('#patient-loading-div').hide();
                $('#report_table > tbody').html('');
                $('#report_weekly_table > tbody').html('');
                $('#report_weekly_table > thead > tr').html('');
                if (r.data.overall.length > 0) {
                    $('#report_table > tbody').append('<tr><td>Staff</td><td>User Count</td></tr>')

                    $.each(r.data.overall, function(i, v) {
                        var tr = $('<tr>');
                        if (v.staff.staff_name == '' || v.staff.staff_name==undefined) {
                            var tdStaffName = $('<td>').text('Autoassign');
                        } else {
                            var tdStaffName = $('<td>').text(v.staff.staff_name);
                        }
                        var tdUserCount = $('<td>').append($('<b class="green">').text(v[0]['user_count']));
                        $('#report_table > tbody').append(tr.append(tdStaffName).append(tdUserCount));
                    });
                    $('#report_div,.overall').show();
                    $('#report_total_count').html('<b>Total</b> : '+r.data.total+'');
                    $('#report_total_count,.overall').show();
                    var count = 0;
                    var tr = $('<tr>');
                    var weeklyData = r.data.weekly;
                    var dateRanges = $('<tr><td></td></tr>');
                    $.each(weeklyData.weekRanges, function(i, v) {
                        dateRanges.append('<td>' + v + '</td>');
                        $('#report_weekly_table > tbody').append(dateRanges);
                    });

                    $.each(weeklyData.dataSet, function(i, v) {
                        var staffRanges = $('<tr>');
                        var staffDataTd = $('<td>' + i + '</td>');
                        staffRanges.append(staffDataTd);

                        $.each(v, function(key, value) {
                            staffRanges.append($('<td>' + value + '</td>'));
                        });
                        $('#report_weekly_table > tbody').append(staffRanges);
                    });
                    $('.weekly, #report_weekly_div').show();
                } else {
                    $('.weekly,.overall').hide();
                    $('#report_div').show();
                    $('#report_weekly_div').show();
                    $('#report_table > tbody').append('<tr><td colspan="2" style="text-align: center;">No Record Found</td></tr>');
                    $('#report_weekly_table > tbody').append('<tr><td colspan="2" style="text-align: center;">No Record Found</td></tr>');
                    $('#report_table').show();
                    $('#report_total_count').hide();
                }
            }
        });
    });
});
