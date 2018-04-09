$(document).on('change', '.change-job-status', function() {
    var status = $(this).val();
    var job = $(this).attr('id');
    var userid = $(this).attr('useriditem');
    var block = $(this).parent().parent().parent();
    var tab = $(this).parent().parent().parent().parent().parent();
    var url = $(this).data('url');
    var children_class;
    var empty_class;
    var r_children_class;
    var r_empty_class;

    $.ajax({
        url:  url,
        type: "POST",
        data: {job: job, status: status, user: userid},
        dataType: 'json',
        success:function(data) {
            var jobs_liked      = data['jobs_liked'].length;
            var jobs_applied    = data['jobs_applied'].length;
            $("#liked_job_count").html("Liked (" + jobs_liked + ")");
            $("#applied_job_count").html("Applied (" + jobs_applied + ")");

            if (status === 'Applied' || status === 'Pending') {
                var applied = block;
                var statuses;

                if (tab.attr('id') === 'tab_1-1' && status === 'Applied') {
                    children_class = ".applied-jobs-div";
                    empty_class = '.empty-applied';
                    r_children_class = ".liked-jobs-div";
                    r_empty_class = '.empty-liked';

                    if (status === 'Applied') {
                        statuses = '<option value="Pending">Pending</option>' +
                            '<option selected value="Applied">Applied</option>' +
                            '<option value="Interview">Interview</option>' +
                            '<option value="Offer">Offer</option>' +
                            '<option value="Rejected">Rejected</option>';
                    }
                } else if (tab.attr('id') === 'tab_2-2' && status === 'Pending') {
                    children_class = ".liked-jobs-div";
                    empty_class = '.empty-liked';
                    r_children_class = ".applied-jobs-div";
                    r_empty_class = '.empty-applied';
                    statuses = '<option selected value="Pending">Pending</option>' +
                        '<option value="Applied">Applied</option>' +
                        '<option value="Ready">Ready</option>' +
                        '<option value="Need Info">Need Info</option>';
                } else {
                    children_class = '.' + applied.parent().attr('class');
                    if (children_class === '.liked-jobs-div') {
                        r_empty_class = empty_class = '.empty-liked';
                        r_children_class = children_class;
                    } else {
                        r_empty_class = empty_class = '.empty-applied';
                        r_children_class = children_class;
                    }
                }

                var select = $(applied).children('.change-job-status').context;

                $(select).html(statuses);
                block.remove();
                $(applied).find('span.job-date').html("FMP Applied: " + moment().format('MMM D, YYYY'));
                applied.appendTo(children_class);
                var job_sort = $(children_class).children();
                job_sort.sort(function (a, b) {
                    return new Date($(a).attr('data-sort')) < new Date($(b).attr('data-sort'));
                });

                $(children_class).html(job_sort);

            }

            getSuccessNoty('Job data was changed');
            $(".job-apply-filter").trigger("change");
            $(".job-like-filter").trigger("change");
        }
    });
});

$(document).on('change', '.inputfile', function() {
    var block = $(this).parent();
    var data = new FormData();
    var url = $(this).attr('data-href');
    data.append('file', $(this)[0].files[0]);

    $.ajax({
        url:  url,
        type: "POST",
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        success: function(data) {
            data = JSON.parse(data);

            var html = '<label style="padding: 0.625rem 1.25rem;">' +
                '<span>' +
                '<a href="' + data.file_url + '">' + data.filename + '</a>' +
            '<a href="#" data-href="' + data.remove_url + '" data-href-add="' + data.add_url + '" class="remove-cover-letter"><i class="fa fa-close"></i></a>' +
            '</span></label>';

            $(block).html(html);
            getSuccessNoty('Cover letter upload successfully')
        },
        error: function() {
            getErrorNoty('Available formats .pdf, .doc, .docx, .rtf, .txt');
        }
    });
});

$(document).on('click', '.remove-cover-letter', function(e) {
    e.preventDefault();
    var block = $(this).parent().parent().parent();
    var url = $(this).attr('data-href');
    var add = $(this).attr('data-href-add');
    var id = $(this).attr('data-id');

    var html = '<input type="file" id="coverLatter' + id + '" class="inputfile inputfile-3" accept=".pdf, .doc, .docx, .rtf, .txt" data-href="' + add + '" />' +
    '<label for="coverLatter' + id + '"><span>Upload Cover Letter</span></label>';

    $.ajax({
        url:  url,
        type: "GET",
        success: function() {
            $(block).html(html);
            getSuccessNoty('Cover letter remove successfully')
        }
    });
});

$(document).on('change', '.job-like-filter', function() {
    var filter = $(this).val();
    var jobs = $('.liked-jobs-div').children();
    if (filter !== '') {
        jobs.css('display', 'block').filter(function( index ) {
            return $(".change-job-status", this).val() !== filter;
        }).css('display', 'none');
        // getSuccessNoty('Filtered');
    } else {
        jobs.css('display', 'block');
    }
});

$(document).on('change', '.job-apply-filter', function() {
    var filter = $(this).val();
    var jobs = $('.applied-jobs-div').children();
    if (filter !== '') {
        jobs.css('display', 'block').filter(function( index ) {
            return $(".change-job-status", this).val() !== filter;
        }).css('display', 'none');
        // getSuccessNoty('Filtered');
    } else {
        jobs.css('display', 'block');
    }
});

$(".close-contact-modal-btn").click(function(){
    $("#contact-modal").modal('hide');
});

$(".close-contact-view-modal-btn").click(function(){
    $("#contact-view-modal").modal('hide');
    // $("#contact-view-modal").removeClass("in");
    // $("#contact-view-modal").removeProp("aria-hidden");
});
