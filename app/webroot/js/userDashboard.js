var xhrActivities;
var xhrPoints;
var xhrLiked;
var xhrClinics;
var xhrSaved;
var xhrCheckins;
var xhrUpdateProfile;
var obj = [];
var userAge;
$(document).ready(function() {
    $('<div id="backdrop" class="modal-backdrop fade in text-center" data-backdrop="static" style="display:none;"><span>Please Wait...</span></div>').appendTo(document.body);
    $('#user-profile .profile-update-error').remove();
    calculateAge();
    editAvatar();
    getPoints();
    getCheckIns();
    getMoreCheckIns();
    getActivity();
    getMoreActivity();
    getSaved();
    getClinic();
    getMoreSaved();
    unlikeClinic();
    deleteDoctor();
    getMoreLiked();
    getLiked();
    getLiked1();
    redeemPoints();
    redeemPointMain();
    //submitRedeemPoints();
    cancelEdit();
    updateProfile();
    confirmDelete();
    cancelProfileChanges();
    cancelProfileUpdate();
    if ($('.age-container .span-value-medium').html().length > 0 && $('.age-container .span-value-medium').html() >= 13) {
        $('.add-email-outer-container').addClass('disabled');
        $('.add-email-outer-container').hide();
    }
});

function confirmDelete() {
    $(document).on("click", "#confirmDelete", function() {
        var userId = $('#user-id').val();

        var dataType = $(this).attr('data-type');
        if (dataType == "saved") {
            var docId = $(this).attr('data-id');
            $.ajax({
                type: 'POST',
                url: '/buzzydoc/unsavedoctor',
                dataType: 'json',
                data: {
                    doctor_id: docId,
                    user_id: userId
                },
                success: function(r) {
                    if (r.success) {
                        var svdc = $('#svdoc').text();

                        $('#panel-saved-doc' + docId).remove();
                        $('#tab-saved .line-height-1.bigger-170').html(r.likes);
                        $('#confirmDeleteModal').modal().hide();
                        var $newDataContainer = $('<div class="delete-success alert alert-success alert-dismissible text-center" role="alert"></div>');
                        $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                        $newDataContainer.append('<span>Deleted Doctor Successfully.</span>');
//                        $('#svdoc').text(svdc - 1);
                        $('#savedList').before($newDataContainer);
                        $('body').removeClass('modal-open');
                    }
                }
            });
        } else if (dataType == "liked") {
            var clinicId = $(this).attr('data-id');
            $.ajax({
                type: 'POST',
                url: '/buzzydoc/unlikeclinic',
                dataType: 'json',
                data: {
                    clinic_id: clinicId,
                    user_id: userId
                },
                success: function(r) {
                    if (r.success) {
                        var lkcl = $('#lkcl').text();
                        $('#clinic' + clinicId).remove();
                        $('#tab-liked .line-height-1.bigger-170').html(r.likes);
                        $('#confirmDeleteModal').modal().hide();
                        var $newDataContainer = $('<div class="delete-success alert alert-success alert-dismissible text-center" role="alert"></div>');
                        $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                        $newDataContainer.append('<span>Successfully unlinked the practice.</span>');
                        $('#lkcl').text(lkcl - 1);
                        $('#likedList').before($newDataContainer);
                    }
                }
            });
        }
        $('#confirmDelete').attr('data-id', '');
        $('#confirmDelete').attr('data-type', '');

    });
}
function deleteDoctor() {
    $(document).on("click", ".delete-doctor", function() {
        var docId = $(this).attr('data-id');
        $('#confirmDelete').attr('data-id', docId);
        $('#confirmDelete').attr('data-type', 'saved');
        $('#confirmDeleteModal').modal().fadeIn(100);
    });
}
function validateEmail(email) {
    var re = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return re.test(email);
}
function validatePhone(email) {
    var re = /^([0-9])+$/;
    return re.test(email);
}
function validateZip(email) {
    var re = /^([a-zA-Z0-9])+$/;
    return re.test(email);
}
function unlikeClinic() {
    $(document).on("click", ".clinic-overlay-delete", function() {
        var userId = $('#user-id').val();
        var clinicId = $(this).attr('data-clinic-id');
        $('#confirmDelete').attr('data-id', clinicId);
        $('#confirmDelete').attr('data-type', 'liked');
        $('#confirmDeleteModal').modal().fadeIn(100);
    });
}

function renderCheckins(offset, limit, timestamp, user_id) {
    if (!xhrCheckins) {
        xhrCheckins = $.ajax({
            type: 'POST',
            url: '/api/checkins.json',
            dataType: 'json',
            data: {
                offset: offset,
                limit: limit,
                timestamp: timestamp,
                user_id: user_id,
            },
            success: function(r) {
                if (r.checkins.success == true) {
                    if (r.checkins.data.length > 0) {
                        $('#more-checkins').show();
                        for (x in r.checkins.data) {
                            var classValue = "";
                            (x % 2 == 0) ? classValue = 'odd' : '';
                            var $newDataContainer = $('<div class="panel panel-default ' + classValue + '"></div>');
                            $newDataContainer.append('<div class="panel-heading collapsed see-more-gift" role="tab" id="checkins-points-panel' + x + '" data-toggle="collapse" data-parent="#checkinsPoints" href="#checkins-points' + (offset + x) + '" aria-expanded="true" aria-controls="checkins-points' + x + '"></div>');
                            $newDataContainer.find('.panel-heading.collapsed').append('<h4 class="panel-title"><span>' + r.checkins.data[x].doctor_name + '</span> gave points <strong>' + r.checkins.data[x].Transaction.amount + '</strong> ' + r.checkins.data[x].Transaction.authorization + '</h4><span class="up-down-arrow"><i class="arrow fa fa-angle-up"></i><i class="arrow fa fa-angle-down"></i></span>');
                            $newDataContainer.append('<div id="checkins-points' + (offset + x) + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="checkins-points-panel' + x + '"></div>');
                            $newDataContainer.find('.panel-collapse.collapse').append('<div class="panel-body"></div>');
                            $newDataContainer.find('.panel-body').append('<div class="row"></div>');
                            $newDataContainer.find('.row').append('<div class="col-lg-6 buzzy-point-inner-data-container"></div>');
                            $newDataContainer.find('.buzzy-point-inner-data-container').append('<div class="media clearfix"></div>');
                            $newDataContainer.find('.media').append('<a class="pull-left checkin-user" href="#"><img src= ' + r.checkins.data[x].doctor_image + ' class="clinic-img img-responsive"></img></a>');
                            $newDataContainer.find('.media').append('<div class="media-body"><h4 class="media-heading">' + r.checkins.data[x].doctor_name + '</h4></div>');
                            $newDataContainer.find('.media-body').append('<div><a class="comment-user-name" href="#"> ' + r.checkins.data[x].Transaction.date + ' </a></div>');
                            $newDataContainer.find('.row').append('<div class="col-lg-6 buzzy-point-inner-value-container"></div>');
                            $newDataContainer.find('.buzzy-point-inner-value-container').append('<div class="row"></div>');
                            $newDataContainer.find('.buzzy-point-inner-value-container .row').append('<div class="col-lg-6 buzzy-point-container"></div>');
                            $newDataContainer.find('.buzzy-point-container').append('<span class="clinic-local-points">' + r.checkins.data[x].Transaction.amount + ' <span style="font-size:20px;">Points</span></span>');
                            $newDataContainer.find('.buzzy-point-inner-value-container .row').append('<div class="col-lg-6 points-reason-outer-container"></div>');
                            $newDataContainer.find('.points-reason-outer-container').append('<div class="points-reason-container"><span class="redeem-points-reason">' + r.checkins.data[x].Transaction.authorization + '</span></div>');
                            $("#checkinsPoints").append($newDataContainer);
                        }
                        xhrCheckins = false;
                        if (r.checkins.data.length < limit) {
                            $('#more-checkins').remove();
                        }
                    } else {
                        if (offset == 0) {
                            var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                            $newDataContainer.append('<span>The list is empty.</span>');
                            $('#checkinsPoints').before($newDataContainer);
                        }
                        $('#more-checkins').remove();
                    }
                } else {
                    if (offset == 0) {
                        var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                        $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                        $newDataContainer.append('<span>The list is empty.</span>');
                        $('#checkinsPoints').before($newDataContainer);
                    }
                }
            },
            beforeSend: function() {
                $('.tabbable .loading').show();
            },
            complete: function() {
                $('.tabbable .loading').hide();
            }
        });
    }
}
function getCheckIns() {
    $('#tab-checkins').on('click', function() {
        var timestamp = $('#ren').val();
        $('#more-checkins').hide();
        renderCheckins($('#checkinsPoints .panel.panel-default').length, 10, timestamp, $('#user-id').val());
    });
}
function getMoreCheckIns() {
    $('#more-checkins').on("click", function() {
        var timestamp = $('#ren').val();
        renderCheckins($('#checkinsPoints .panel.panel-default').length, 10, timestamp, $('#user-id').val());
    });
}
function renderSaved(offset, limit, timestamp, user_id) {
    if (!xhrSaved) {
        xhrSaved = $.ajax({
            type: 'POST',
            url: '/api/saveddoctors.json',
            dataType: 'json',
            data: {
                offset: offset,
                limit: limit,
                timestamp: timestamp,
                user_id: user_id,
            },
            success: function(r) {
                if (r.saveddoctors.success == true) {
                    if (r.saveddoctors.data.length > 0) {
                        $('#more-saved').show();
                        for (x in r.saveddoctors.data) {
                            var classValue = "";
                            (x % 2 == 0) ? classValue = ' odd' : '';
                            var $newDataContainer = $('<div id="panel-saved-doc' + r.saveddoctors.data[x].Doctor.id + '" class="panel panel-default margin-bottom-medium' + classValue + '"></div>');
                            $newDataContainer.append('<div class="panel-heading collapsed see-more-gift" role="tab" id="saved-doc-panel' + (offset + x) + '" data-toggle="collapse" data-parent="#savedList" href="#saved-doc' + (offset + x) + '" aria-expanded="true" aria-controls="saved-doc-panel' + (offset + x) + '"></div>');
                            $newDataContainer.find('.panel-heading.collapsed').append("<h4 class='panel-title clearfix'><span>Dr. " + r.saveddoctors.data[x].Doctor.first_name + " " + r.saveddoctors.data[x].Doctor.last_name + ", " + r.saveddoctors.data[x].Doctor.degree + "</span></h4><span class='up-down-arrow'><i class='arrow fa fa-angle-up'></i><i class='arrow fa fa-angle-down'></i></span>");
                            $newDataContainer.find('.panel-title').append('<span class="pull-right" style="margin-right: 20px;">Specializes in <strong>' + r.saveddoctors.data[x].Doctor.specialty + '</strong></span></h4>');
                            $newDataContainer.append('<div id="saved-doc' + (offset + x) + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="saved-doc-panel' + (offset + x) + '"></div>');
                            $newDataContainer.find('.panel-collapse.collapse').append('<div class="panel-body"></div>');
                            $newDataContainer.find('.panel-body').append('<div class="row doc-intro"></div>');
                            $newDataContainer.find('.doc-intro').append('<div class="col-lg-6 buzzy-point-inner-data-container"></div>');
                            $newDataContainer.find('.buzzy-point-inner-data-container').append('<div class="media clearfix"></div>');
                            $newDataContainer.find('.media').append('<a class="pull-left" href="' + r.saveddoctors.data[x].Doctor.buzzyurl + '"><img src= ' + r.saveddoctors.data[x].Doctor.docimg + ' class="clinic-img img-responsive thumbnail"></img></a>');
                            $newDataContainer.find('.media').append("<div class='media-body'><h4 class='media-heading'><strong><a href='" + r.saveddoctors.data[x].Doctor.buzzyurl + "'>Dr. " + r.saveddoctors.data[x].Doctor.first_name + " " + r.saveddoctors.data[x].Doctor.last_name + "</a></strong></h4></div>");
                            $newDataContainer.find('.media-body').append('<div>Specializes in <strong>' + r.saveddoctors.data[x].Doctor.specialty + '</strong></div>');
                            $newDataContainer.find('.media-body').append('<div>Degree: <strong>' + r.saveddoctors.data[x].Doctor.degree + '</strong></div>');
                            $newDataContainer.find('.buzzy-point-inner-data-container').append('<div class="row doc-info-outer-container"></div>');
                            $newDataContainer.find('.doc-info-outer-container').append('<div class="col-lg-12 text-left  margin-top-small">Email: <strong>' + r.saveddoctors.data[x].Doctor.email + '</strong></div>');
                            $newDataContainer.find('.doc-info-outer-container').append('<div class="col-lg-12 text-left margin-top-small">Phone: <strong>' + r.saveddoctors.data[x].Doctor.phone + '</strong></div>');
                            $newDataContainer.find('.doc-info-outer-container').append('<div class="col-lg-12 text-left margin-top-small">Gender: <strong>' + r.saveddoctors.data[x].Doctor.gender + '</strong></div>');
                            $newDataContainer.find('.doc-intro').append('<div class="col-lg-6 doctor-description"><h4><strong>About Doctor:</strong></h4></div>');
                            $newDataContainer.find('.doctor-description').append('<div class=""><span>' + r.saveddoctors.data[x].Doctor.description + '</span></div>');
                            $newDataContainer.find('#saved-doc' + (offset + x)).append('<div class="usersave-panel-footer text-center"><span class="btn btn-danger  delete-doctor"  data-id="' + r.saveddoctors.data[x].Doctor.id + '">Delete</span></div>');
                            $("#savedList").append($newDataContainer);
                        }
                        if (r.saveddoctors.data.length < limit) {
                            $('#more-saved').remove();
                        }
                        xhrSaved = false;
                    } else {
                        $('#more-saved').remove();
                        if (offset == 0) {
                            var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                            $newDataContainer.append('<span>The list is empty.</span>');
                            $('#savedList').before($newDataContainer);
                        }
                    }
                } else {
                    $('#more-saved').hide();
                    if (offset == 0) {
                        var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                        $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                        $newDataContainer.append('<span>The list is empty.</span>');
                        $('#savedList').before($newDataContainer);
                    }
                }
            },
            beforeSend: function() {
                $('.tabbable .loading').show();
            },
            complete: function() {
                $('.tabbable .loading').hide();
            }
        });
    }
}
function getSaved() {
    $('#tab-saved').on('click', function() {
        var timestamp = $('#ren').val();
        $('.delete-success').remove();
        $('#more-saved').hide();
        renderSaved($('#savedList .panel.panel-default').length, 10, timestamp, $('#user-id').val());
    });
}
function renderClinic(offset, user_id) {
    if (!xhrClinics) {
        xhrClinics = $.ajax({
            type: 'POST',
            url: '/api/myclinic.json',
            dataType: 'json',
            data: {
                user_id: user_id
            },
            success: function(r) {
                if (r.myclinic.success == true) {
                    if (r.myclinic.data.length > 0) {
                            $("#clinicList").html('');
                        for (x in r.myclinic.data) {
                            
                            var $newDataContainer = $('<a id="clinic' + r.myclinic.data[x].Clinic.id + '" class="liked-clinic" href="javascript:void(0)"></a>');
                            $newDataContainer.append('<div class="clinic-with-overlay"></div>');
                            $newDataContainer.find('.clinic-with-overlay').append('<a href="' + r.myclinic.data[x].Clinic.buzzyclinicurl + '"><img src="' + r.myclinic.data[x].Clinic.clinicimg + '" class="clinic-image"></a>');
                            $newDataContainer.find('.clinic-with-overlay').append('<div class="clinic-overlay-detail"></div>');
                            $newDataContainer.find('.clinic-overlay-detail').append('<div class="clinic-overlay-name"><a href="' + r.myclinic.data[x].Clinic.buzzyclinicurl + '">' + r.myclinic.data[x].Clinic.api_user + '</a></div>');
                            $newDataContainer.find('.clinic-overlay-detail').append('</div></div>');
                            $("#clinicList").append($newDataContainer);
                        }
                        xhrClinics = false;
                    
                    } else {
                        if (offset == 0) {
                            var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                            $newDataContainer.append('<span>The list is empty.</span>');
                            $('#clinicList').before($newDataContainer);
                        }
                        
                    }
                } else {
         if (offset == 0) {
                        var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                        $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                        $newDataContainer.append('<span>The list is empty.</span>');
                        $('#clinicList').before($newDataContainer);
                    }
                    
                }
            },
            beforeSend: function() {
                $('.tabbable .loading1').show();
            },
            complete: function() {
                $('.tabbable .loading1').hide();
            }
        });
    }
}
function getClinic() {
    $('#tab-saved').on('click', function() {
        $('.delete-success').remove();
        renderClinic($('#clinicList a.liked-clinic').length, $('#user-id').val());
    });
}
function getMoreSaved() {
    $('#more-saved').on("click", function() {
        var timestamp = $('#ren').val();
        $('.delete-success').remove();
        renderSaved($('#savedList .panel.panel-default').length, 10, timestamp, $('#user-id').val());
    });
}

function renderLiked(offset, limit, timestamp, user_id) {
    if (!xhrLiked) {
        xhrLiked = $.ajax({
            type: 'POST',
            url: '/api/likedclinic.json',
            dataType: 'json',
            data: {
                offset: offset,
                limit: limit,
                timestamp: timestamp,
                user_id: user_id
            },
            success: function(r) {
                if (r.likedclinic.success == true) {
                    if (r.likedclinic.data.length > 0) {
                        $('#more-liked').show();
                        for (x in r.likedclinic.data) {
                            var $newDataContainer = $('<a id="clinic' + r.likedclinic.data[x].Clinic.id + '" class="liked-clinic" href="javascript:void(0)"></a>');
                            $newDataContainer.append('<div class="clinic-with-overlay"></div>');
                            $newDataContainer.find('.clinic-with-overlay').append('<a href="' + r.likedclinic.data[x].Clinic.buzzyclinicurl + '"><img src="' + r.likedclinic.data[x].Clinic.clinicimg + '" class="clinic-image"></a>');
                            $newDataContainer.find('.clinic-with-overlay').append('<div class="clinic-overlay-detail"></div>');
                            $newDataContainer.find('.clinic-overlay-detail').append('<div class="clinic-overlay-name"><a href="' + r.likedclinic.data[x].Clinic.buzzyclinicurl + '">' + r.likedclinic.data[x].Clinic.api_user + '</a></div>');
                            $newDataContainer.find('.clinic-overlay-detail').append('<div class="clinic-overlay-delete" data-clinic-id=' + r.likedclinic.data[x].Clinic.id + '><span class="glyphicon glyphicon-trash"></span><div></div></div>');
                            $("#likedList").append($newDataContainer);
                        }
                        xhrLiked = false;
                        if (r.likedclinic.data.length < limit) {
                            $('#more-liked').remove();
                        }
                    } else {
                        $('#more-liked').remove();
                        if (offset == 0) {
                            var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                            $newDataContainer.append('<span>The list is empty.</span>');
                            $('#likedList').before($newDataContainer);
                        }
                    }
                } else {
                    $('#more-liked').remove();
                    if (offset == 0) {
                        var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                        $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                        $newDataContainer.append('<span>The list is empty.</span>');
                        $('#likedList').before($newDataContainer);
                    }
                }
            },
            beforeSend: function() {
                $('.tabbable .loading').show();
            },
            complete: function() {
                $('.tabbable .loading').hide();
            }
        });
    }
}
function getLiked() {
    $('#tab-earn').on('click', function() {
        var timestamp = $('#ren').val();
        $('#more-liked').hide();
        $('.delete-success').remove();
        renderLiked($('#likedList a.liked-clinic').length, 10, timestamp, $('#user-id').val());
    });
}
function getLiked1() {
    $('#tab-liked1').on('click', function() {
        var timestamp = $('#ren').val();
        $('#more-liked').hide();
        $('.delete-success').remove();
        renderLiked($('#likedList a.liked-clinic').length, 10, timestamp, $('#user-id').val());
    });
}
function getMoreLiked() {
    $('#more-liked').on("click", function() {
        $('.delete-success').remove();
        var timestamp = $('#ren').val();
        renderLiked($('#likedList a.liked-clinic').length, 10, timestamp, $('#user-id').val());
    });
}


function submitRedeemPoints(sku) {
    var buzzPoints = parseInt($('#buzz_point').val());
    var arr = sku.split('-');
    if(arr[0]=='AMZN'){
        var card='an Amazon';
    }else{
        var card='a Tango';
    }
    var r = confirm("You are about to redeem "+buzzPoints+" of your BuzzyDoc Points for "+card+" Gift Card. Would you like to proceed?");
        if (r == true)
        {
    if(sku=='AMZN-E-V-STD'){
    $('#load-status1').show();
    $('#submit-redeem2').attr('disabled', 'disabled');
    }else{
     
    $('#load-status').show();
    $('#submit-redeem').attr('disabled', 'disabled');
    $('#submit-redeem1').attr('disabled', 'disabled');
    }

    

    var clinic_id = parseInt($('#clinic_id').val());
    if (buzzPoints > 0) {
        $('.input-error').remove();

        $.ajax({
            type: 'POST',
            url: '/buzzydoc/placeorder',
            dataType: 'json',
            data: {
                sku: sku, //$('#recent-adcitivies a').length,
                clinic_id: clinic_id,
                amount: buzzPoints,
                user_id: $('#user-id').val()
            },
            success: function(r) {
                if (r.success == true) {
                    if (r.error != '') {
                        var errcl = 'Partial redemption successful. Check your e-mail. Transaction for ' + r.error + ' has failed. Your points are safe in your account.'
                    } else {
                        var errcl = 'Redemption successful. Check your e-mail.';
                    }
                    $('#load-status').hide();
                    $('#load-status1').hide();
                    var total = $('#toppoint').text();
                    $('.input-error').remove();
                    $('#shgbpoint').text(r.pointremain);
                    $('#buzz_point').text(r.pointremain);
                    $('.global-point-value').text(r.pointremain);

                    $('#toppoint').text(r.pointremain);
                    $('#midpoint').text(r.pointremain);
//                        $(this).attr('data-points').val('0');
                    var remindol = r.pointremain / 50;
                    $('.theme-bold-text').text(r.pointremain + ' ( ' + remindol + ' $)');
                    var $newDataContainer = $('<div class="input-error alert alert-danger alert-dismissible" role="alert"></div>');
                    $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                    $newDataContainer.append('<span>' + errcl + '</span>');
                    if(sku=='AMZN-E-V-STD'){
                    $('#redeemModalMain .modal-body .points-value-span-container1').before($newDataContainer);
                    }else{
                    $('#redeemModalMain .modal-body .points-value-span-container1').before($newDataContainer);   
                    }
                    location.reload();
//                        $('#redeemModal').modal('hide');
                } else {
                    if (r.error != '') {
                        var errcl = 'Check your e-mail. Transaction for ' + r.error + ' has failed. Your points are safe in your account.'
                    } else {
                        var errcl = '';
                    }
                    $('#load-status').hide();
                    $('#load-status1').hide();
                    $('#submit-redeem').removeAttr('disabled');
                    $('#submit-redeem1').removeAttr('disabled');
                    $('#submit-redeem2').removeAttr('disabled');
                    alert('Unable to redeem. Please contact buzzydoc admin.' + errcl);
                }
            }
        });

    } else {
        $('.input-error').remove();
        $('#load-status').hide();
        $('#load-status1').hide();
        var $newDataContainer = $('<div class="input-error alert alert-danger alert-dismissible" role="alert"></div>');
        $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
        $newDataContainer.append('<span>You do not have sufficient balance</span>');
        if(sku=='AMZN-E-V-STD'){
        $('#redeemModalMain .modal-body .points-value-span-container1').before($newDataContainer);
        }else{
         $('#redeemModalMain .modal-body .points-value-span-container1').before($newDataContainer);   
        }  
    }
        }else{
            return false;
        }

}
function redeemPoints() {
    
    $(document).on("click", ".redeem-points-button-container", function() {
        $('#redeemModalMain').modal().fadeOut(100);
        $('#load-status1').hide();
        $('#submit-redeem').removeAttr('disabled');
        $('#submit-redeem1').removeAttr('disabled');
        $('#submit-redeem2').removeAttr('disabled');
        var points = $('.global-point-value').text();
        var point_type = $(this).attr('data-type');
        var clinic_id = $(this).attr('data-clinicId');
            $('#point_type').val(point_type);
            $('#buzz_point').val(points);
            $('#clinic_id').val(clinic_id);
            var pointsindol = points / 50;
            var num = parseFloat(pointsindol);
            var new_num = num.toFixed(2);
            $('#points_value_span').html('You have <span class="theme-bold-text">' + points + ' ($' + new_num + ')</span> Buzzydoc Points to redeem!');
        
        $('.input-error').remove();
        $('#redeemModal').modal().fadeIn(100);
        $('#points_value').empty();
    });
}
function redeemPointMain() {

    $(document).on("click", ".redeem-points-button-main-container", function() {
        $('#load-status').hide();
        $('#submit-redeem').removeAttr('disabled');
        $('#submit-redeem1').removeAttr('disabled');
        var points = $('.global-point-value').text();
        var point_type = $(this).attr('data-type');
        var clinic_id = $(this).attr('data-clinicId');
            $('#point_type').val(point_type);
            $('#buzz_point').val(points);
            $('#clinic_id').val(clinic_id);
            var pointsindol = points / 50;
            var num = parseFloat(pointsindol);
            var new_num = num.toFixed(2);
            $('#points_value_span1').html('You have <span class="theme-bold-text">' + points + '</span> Buzzydoc Points to redeem!<br />That\'s $' + new_num + ' in prizes just waiting for you! ');
        
        $('.input-error').remove();
        $('#redeemModalMain').modal().fadeIn(100);
        $('#points_value').empty();
    });
}
function getPoints() {

    $('#tab-points').on('click', function() {

        if (!xhrPoints) {
            var timestamp = $('#ren').val();

            $("#buz-points").empty();
            xhrPoints = $.ajax({
                type: 'POST',
                url: '/api/userspoints.json',
                dataType: 'json',
                data: {
                    offset: 0, //$('#recent-adcitivies a').length,
                    limit: 50,
                    timestamp: timestamp,
                    user_id: $('#user-id').val()
                },
                success: function(r) {
                    var getcheck = $('#getpointcnt').val();
                    if (getcheck == 0) {
                        if (r.userspoints.success == true) {
                            $('#getpointcnt').val(1);
                            if (r.userspoints.data.length > 0) {
                                for (x in r.userspoints.data) {
                                    if(r.userspoints.data[x].ClinicUser.local_points>0){
                                    var $newDataContainer = $('<div class="panel panel-default"></div>');
                                    $newDataContainer.append('<div class="panel-heading collapsed" role="tab" id="buzzyDoc-local' + x + '" data-toggle="collapse" data-parent="#accordionLocal" href="#buzzyDoc-local-clinic-points' + x + '" aria-expanded="true" aria-controls="buzzyDoc-local-clinic-points' + x + '"></div>');
                                    $newDataContainer.find('.panel-heading.collapsed').append('<h4 class="panel-title clearfix"><span>' + r.userspoints.data[x].clinicname + '</span></h4>');
                                    if (r.userspoints.data[x].ClinicUser.local_points == null) {
                                        ptsVal = 0;
                                    } else {
                                        ptsVal = r.userspoints.data[x].ClinicUser.local_points;
                                    }
                                    $newDataContainer.find('.panel-heading.collapsed h4').append('<span class="pull-right clinicpoint">' + ptsVal + '</span>');
                                    $newDataContainer.append('<div id="buzzyDoc-local-clinic-points' + x + '" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="buzzyDoc-local-clinic-points' + x + '"></div>');
                                    $newDataContainer.find('.panel-collapse.collapse').append('<div class="panel-body"></div>');
                                    $newDataContainer.find('.panel-body').append('<div class="row"></div>');
                                    $newDataContainer.find('.row').append('<div class="col-lg-6 buzzy-point-inner-data-container"></div>');
                                    $newDataContainer.find('.buzzy-point-inner-data-container').append('<div class="media clearfix"></div>');
                                    $newDataContainer.find('.media').append('<a class="pull-left" href="#"><img src= ' + r.userspoints.data[x].cliniclogo + ' class="clinic-img img-responsive"></img></a>');
                                    $newDataContainer.find('.media').append('<div class="media-body"><h4 class="media-heading">' + r.userspoints.data[x].clinicname + '</h4></div>');
//                                $newDataContainer.find('.media-body').append('<div><a class="comment-user-name" href="#"> ' + r.userspoints.data[x].clinicname + ' </a></div>');
                                    $newDataContainer.find('.row').append('<div class="col-lg-6 buzzy-point-inner-value-container"></div>');
                                    $newDataContainer.find('.buzzy-point-inner-value-container').append('<div class="row"></div>');
                                    $newDataContainer.find('.buzzy-point-inner-value-container .row').append('<div class="col-lg-6 buzzy-point-container"></div>');
                                    $newDataContainer.find('.buzzy-point-container').append('<span class="clinic-local-points">' + ptsVal + '</span>');
                                    $newDataContainer.find('.buzzy-point-inner-value-container .row').append('<div class="col-lg-6 buzzy-redeem-button-container"></div>');


                                    $newDataContainer.find('.buzzy-redeem-button-container').append('<div class="btn btn-success redeem-points-button-container1"><span class="redeem-points-button"><a href="' + r.userspoints.data[x].clinicurl + '" target="_blank" style="color: #fff;">Redeem Points</a></span></div>');
                                    $("#accordionLocal").append($newDataContainer);
                                }
                                }

                                xhrPoints = false;
                            } else {
                                var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                                $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                                $newDataContainer.append('<span>You do not have any buzzy points.</span>');
                                $('#accordionLocal').before($newDataContainer);
                            }
                        } else {
                            var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                            $newDataContainer.append('<span>You do not have any buzzy points.</span>');
                            $('#accordionLocal').before($newDataContainer);
                        }
                    }
                },
                beforeSend: function() {
                    $('.tabbable .loading').show();
                },
                complete: function() {
                    $('.tabbable .loading').hide();
                }
            });
        }
    });

    $('#tab-points1').on('click', function() {
        if (!xhrPoints) {
            var timestamp = $('#ren').val();

            $("#buz-points").empty();
            xhrPoints = $.ajax({
                type: 'POST',
                url: '/api/userspoints.json',
                dataType: 'json',
                data: {
                    offset: 0, //$('#recent-adcitivies a').length,
                    limit: 50,
                    timestamp: timestamp,
                    user_id: $('#user-id').val()
                },
                success: function(r) {
                    var getcheck = $('#getpointcnt').val();
                    if (getcheck == 0) {
                        if (r.userspoints.success == true) {
                            $('#getpointcnt').val(1);
                            if (r.userspoints.data.length > 0) {
                                for (x in r.userspoints.data) {
                                    if(r.userspoints.data[x].ClinicUser.local_points>0){
                                    var $newDataContainer = $('<div class="panel panel-default"></div>');
                                    $newDataContainer.append('<div class="panel-heading collapsed" role="tab" id="buzzyDoc-local' + x + '" data-toggle="collapse" data-parent="#accordionLocal" href="#buzzyDoc-local-clinic-points' + x + '" aria-expanded="true" aria-controls="buzzyDoc-local-clinic-points' + x + '"></div>');
                                    $newDataContainer.find('.panel-heading.collapsed').append('<h4 class="panel-title clearfix"><span>' + r.userspoints.data[x].clinicname + '</span></h4>');
                                    if (r.userspoints.data[x].ClinicUser.local_points == null) {
                                        ptsVal = 0;
                                    } else {
                                        ptsVal = r.userspoints.data[x].ClinicUser.local_points;
                                    }
                                    $newDataContainer.find('.panel-heading.collapsed h4').append('<span class="pull-right clinicpoint">' + ptsVal + '</span>');
                                    $newDataContainer.append('<div id="buzzyDoc-local-clinic-points' + x + '" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="buzzyDoc-local-clinic-points' + x + '"></div>');
                                    $newDataContainer.find('.panel-collapse.collapse').append('<div class="panel-body"></div>');
                                    $newDataContainer.find('.panel-body').append('<div class="row"></div>');
                                    $newDataContainer.find('.row').append('<div class="col-lg-6 buzzy-point-inner-data-container"></div>');
                                    $newDataContainer.find('.buzzy-point-inner-data-container').append('<div class="media clearfix"></div>');
                                    $newDataContainer.find('.media').append('<a class="pull-left" href="#"><img src= ' + r.userspoints.data[x].cliniclogo + ' class="clinic-img img-responsive"></img></a>');
                                    $newDataContainer.find('.media').append('<div class="media-body"><h4 class="media-heading">' + r.userspoints.data[x].clinicname + '</h4></div>');
//                                $newDataContainer.find('.media-body').append('<div><a class="comment-user-name" href="#"> ' + r.userspoints.data[x].clinicname + ' </a></div>');
                                    $newDataContainer.find('.row').append('<div class="col-lg-6 buzzy-point-inner-value-container"></div>');
                                    $newDataContainer.find('.buzzy-point-inner-value-container').append('<div class="row"></div>');
                                    $newDataContainer.find('.buzzy-point-inner-value-container .row').append('<div class="col-lg-6 buzzy-point-container"></div>');
                                    $newDataContainer.find('.buzzy-point-container').append('<span class="clinic-local-points">' + ptsVal + '</span>');
                                    $newDataContainer.find('.buzzy-point-inner-value-container .row').append('<div class="col-lg-6 buzzy-redeem-button-container"></div>');


                                    $newDataContainer.find('.buzzy-redeem-button-container').append('<div class="btn btn-success redeem-points-button-container1"><span class="redeem-points-button"><a href="' + r.userspoints.data[x].clinicurl + '" target="_blank" style="color: #fff;">Redeem Points</a></span></div>');
                                    $("#accordionLocal").append($newDataContainer);
                                }
                                }

                                xhrPoints = false;
                            } else {
                                var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                                $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                                $newDataContainer.append('<span>You do not have any buzzy points.</span>');
                                $('#accordionLocal').before($newDataContainer);
                            }
                        } else {
                            var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                            $newDataContainer.append('<span>You do not have any buzzy points.</span>');
                            $('#accordionLocal').before($newDataContainer);
                        }
                    }
                },
                beforeSend: function() {
                    $('.tabbable .loading').show();
                },
                complete: function() {
                    $('.tabbable .loading').hide();
                }
            });
        }
    });
}

function convertToTimeString(timestamp) {
//    $("abbr.timeago").each(function (timestamp) {
//        var localDate = convertToLocalTimeStamp(timestamp);
    timestring = $.timeago(convertToLocalTimeStamp(timestamp));
    return timestring;
//    });
}

/**
 * method is used to convert date time to local time
 */

function convertToLocalTimeStamp(timestamp) {
    var dateArray = timestamp.toString().split(/[^0-9]/);
    var GMTDate = new Date(dateArray[0], dateArray[1] - 1, dateArray[2], dateArray[3], dateArray[4], dateArray[5]);
    var now = new Date();
    var date = new Date(GMTDate.getTime() - now.getTimezoneOffset() * 60000);
    return date;
}
function getActivity() {
//    $(document).on("click", "#tab-profile", function () {
    var timestamp = $('#ren').val();
    $('#more-checkins').hide();
    renderRecentActivity($('#recent-acitivies .profile-feed').length, 10, timestamp, $('#user-id').val());
//    });
}
function getMoreActivity() {
    $(document).on("click", "#vew-more-activities", function() {
        var timestamp = $('#ren').val();
        $('#more-checkins').hide();
        renderRecentActivity($('#recent-acitivies .profile-feed').length, 10, timestamp, $('#user-id').val());
    });
}
function renderRecentActivity(offset, limit, timestamp, user_id) {
    if (!xhrActivities) {
        var timestamp = $('#ren').val();
        xhrActivities = $.ajax({
            type: 'POST',
            url: '/api/usersactivity.json',
            dataType: 'json',
            data: {
                offset: offset, //$('#recent-adcitivies a').length,
                limit: limit,
                timestamp: timestamp,
                user_id: user_id
            },
            success: function(r) {
                if (r.usersactivity.success == true) {
                    if (r.usersactivity.data.length > 0) {
                        for (x in r.usersactivity.data) {
                            var $newDataContainer = $('<div id="profile-feed' + (offset + x) + '" class="profile-feed margin-small"></div>');
                            $newDataContainer.append('<div class="profile-activity clearfix activity-description"></div>');
                            $newDataContainer.find('.activity-description').append('<div class="media"></div>');
                            $newDataContainer.find('.media').append('<div class="media-body"></div>');
                            var activityText = '';
                            if (r.usersactivity.data[x].Transaction.activity_type == "like clinic") {
                                activityText = 'You Liked a practice <strong>' + r.usersactivity.data[x].Transaction.given_name + '</strong>';
                            } else if (r.usersactivity.data[x].Transaction.activity_type == "save doctor") {
                                activityText = 'You Saved a doctor <strong>' + r.usersactivity.data[x].Transaction.given_name + '</strong>';
                            } else if (r.usersactivity.data[x].Transaction.activity_type == "earn badge") {
                                activityText = 'You Got a new Badge of <strong>' + r.usersactivity.data[x].Transaction.buzzy_name + '</strong>';
                            } else if (r.usersactivity.data[x].Transaction.activity_type == "earn badge") {
                                activityText = 'You Got a new Badge of <strong>' + r.usersactivity.data[x].Transaction.buzzy_name + '</strong>';
                            } else if (r.usersactivity.data[x].Transaction.activity_type == "redeemed") {
                                if(r.usersactivity.data[x].Transaction.amount.substr(1)==''){
                                  var redpoint = 0;  
                                }else{
                                    var redpoint = r.usersactivity.data[x].Transaction.amount.substr(1);
                                }
                                
                                if(r.usersactivity.data[x].Transaction.authorization=='global points expired'){
                                    activityText =  r.usersactivity.data[x].Transaction.authorization + ' for ' + redpoint;    
                                }else if(r.usersactivity.data[x].Transaction.product_service_id>0){
                                    activityText =  r.usersactivity.data[x].Transaction.authorization;  
                                }else{
                                    activityText = 'You have redeemed the reward <strong>' + r.usersactivity.data[x].Transaction.authorization + ' for ' + redpoint + ' points</strong>';
                                }
                                
                            } else if (r.usersactivity.data[x].Transaction.activity_type == "get point") {
                                activityText = '<strong>' + r.usersactivity.data[x].Transaction.given_name + '</strong> gave you points <strong>' + r.usersactivity.data[x].Transaction.amount + '</strong> ' + r.usersactivity.data[x].Transaction.authorization + '</strong>';
                            }
                            $newDataContainer.find('.media-body').append('<div><p> ' + activityText + ' </p></div>');
                            $newDataContainer.find('.media-body').append('<p class="timeSection"><i class="ace-icon fa fa-clock-o bigger-110"></i><a class="margin-top-small"> <abbr class="timeago" title="' + (r.usersactivity.data[x].Transaction.date) + '">' + convertToTimeString(r.usersactivity.data[x].Transaction.date) + '</abbr><a></p>');
                            $("#recent-acitivies").append($newDataContainer);
                            $('#vew-more-activities').show();
//                        convertToTimeString();
                            if (r.usersactivity.data.length < limit) {
                                $('#vew-more-activities').hide();
                            }
                        }
                        xhrActivities = false;
                    } else {
                        $('#vew-more-activities').hide();
                        if (offset == 0) {
                            var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                            $newDataContainer.append('<span>No Activities performed yet.</span>');
                            $('#recent-acitivies').before($newDataContainer);
                        }
                    }

                } else {
                    $('#vew-more-activities').hide();
                    if (offset == 0) {
                        var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                        $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                        $newDataContainer.append('<span>No Activities performed yet.</span>');
                        $('#recent-acitivies').before($newDataContainer);
                    }
                    $('#vew-more-activities').hide();
                }
            }
            ,
            beforeSend: function() {
                $('.tabbable .loading').show();
            },
            complete: function() {
                $('.tabbable .loading').hide();
            }
        });
    }
}
function editAvatar() {
    // *** editable avatar *** //

    try {//ie8 throws some harmless exceptions, so let's catch'em

        //first let's add a fake appendChild method for Image element for browsers that have a problem with this
        //because editable plugin calls appendChild, and it causes errors on IE at unpredicted points
        try {
            document.createElement('IMG').appendChild(document.createElement('B'));
        } catch (e) {
            Image.prototype.appendChild = function(el) {
            }
        }

        var last_gritter;
        $('#avatar,#avatar_popup').editable({
            type: 'image',
            name: 'avatar',
            value: null,
            placement: 'right',
            mode: 'inline',
            image: {
                //specify ace file input plugin's options here
                btn_choose: 'Change Avatar',
                droppable: true,
                maxSize: 512000, //10Mb

                //and a few extra ones here
                name: 'avatar', //put the field name here as well, will be used inside the custom plugin
                on_error: function(error_type) {//on_error function will be called when the selected file has a problem
                    if (last_gritter)
                        $.gritter.remove(last_gritter);
                    if (error_type == 1) {//file format error
                        last_gritter = $.gritter.add({
                            title: 'File is not an image!',
                            text: 'Please choose a jpg|gif|png image!',
                            class_name: 'gritter-error gritter-center'
                        });
                    } else if (error_type == 2) {//file size rror
                        last_gritter = $.gritter.add({
                            title: 'File too big!',
                            text: 'Image size should not exceed 512Kb!',
                            class_name: 'gritter-error gritter-center'
                        });
                    }
                    else {//other error
                    }
                },
                on_success: function() {
                    $.gritter.removeAll();
                }
            },
            url: function(params) {
                var submit_url = '/api/editprofileimage.json';//please modify submit_url accordingly
                var deferred = null;
                var avatar = '#avatar';

//if value is empty (""), it means no valid files were selected
//but it may still be submitted by x-editable plugin
//because "" (empty string) is different from previous non-empty value whatever it was
//so we return just here to prevent problems
                var value = $(avatar).next().find('input[type=hidden]:eq(0)').val();
                if (!value || value.length == 0) {
                    deferred = new $.Deferred
                    deferred.resolve();
                    return deferred.promise();
                }

                var $form = $(avatar).next().find('.editableform:eq(0)');
                var file_input = $form.find('input[type=file]:eq(0)');
                var pk = $(avatar).attr('data-pk');//primary key to be sent to server

                var ie_timeout = null


                if ("FormData" in window) {
                    var formData_object = new FormData();//create empty FormData object

                    //serialize our form (which excludes file inputs)
                    $.each($form.serializeArray(), function(i, item) {
                        //add them one by one to our FormData
                        formData_object.append(item.name, item.value);
                    });
                    //and then add files
                    $form.find('input[type=file]').each(function() {
                        var field_name = $(this).attr('name');
                        var files = $(this).data('ace_input_files');
                        if (files && files.length > 0) {
                            formData_object.append(field_name, files[0]);
                        }
                    });
                    $(avatar).get(0).src = '';
                    //append primary key to our formData
                    formData_object.append('user_id', $('#user-id').val());
                    formData_object.append('email', $('#user-email').val());
                    deferred = $.ajax({
                        url: submit_url,
                        type: 'POST',
                        processData: false, //important
                        contentType: false, //important
                        dataType: 'json', //server response type
                        data: formData_object
                    });
                }
                else {
                    deferred = new $.Deferred
                    $(avatar).get(0).src = '';
                    var temporary_iframe_id = 'temporary-iframe-' + (new Date()).getTime() + '-' + (parseInt(Math.random() * 1000));
                    var temp_iframe =
                            $('<iframe id="' + temporary_iframe_id + '" name="' + temporary_iframe_id + '" \
            frameborder="0" width="0" height="0" src="about:blank"\
            style="position:absolute; z-index:-1; visibility: hidden;"></iframe>')
                            .insertAfter($form);

                    $form.append('<input type="hidden" name="temporary-iframe-id" value="' + temporary_iframe_id + '" />');

                    temp_iframe.data('deferrer', deferred);
                    //we save the deferred object to the iframe and in our server side response
                    //we use "temporary-iframe-id" to access iframe and its deferred object
                    $form.attr({
                        action: submit_url,
                        method: 'POST',
                        enctype: 'multipart/form-data',
                        target: temporary_iframe_id //important
                    });

                    $form.get(0).submit();

                    //if we don't receive any response after 30 seconds, declare it as failed!
                    ie_timeout = setTimeout(function() {
                        ie_timeout = null;
                        temp_iframe.attr('src', 'about:blank').remove();
                        deferred.reject({'status': 'fail', 'message': 'Timeout!'});
                    }, 30000);
                }


//deferred callbacks, triggered by both ajax and iframe solution
                deferred
                        .done(function(result) {//success
                            if (result.editimage.success) {
                                $(avatar).get(0).src = result.editimage.data;
                            }
                            else {
//                                alert(res.message);
                            }
                        })
                        .fail(function(result) {//failure
                            alert("There was an error");
                        })
                        .always(function() {//called on both success and failure
                            if (ie_timeout)
                                clearTimeout(ie_timeout)
                            ie_timeout = null;
                        });

                return deferred.promise();
// ***END OF UPDATE AVATAR HERE***
            },
            success: function(response, newValue) {
                location.reload();
            }
        });
    } catch (e) {
    }

}


function closeKeyBoard() {
    $('input').blur();

}

function cancelProfileChanges() {
    $(document).on('click', '.cancel-edit', function() {
        $('#confirmCancelModal').modal().fadeIn(100);
    });
}
function cancelProfileUpdate() {
    $(document).on('click', '.confirm-update', function() {
        $('#confirmUpdateModal').modal().fadeIn(100);
    });
}
function renderProfileValues() {
    for (x in obj) {
        if (obj[x].key == "fname") {
            $('#input-fname').remove();
            $('.fname-container .span-value-medium').remove();
            $('.fname-container').append('<span class="span-value-medium">' + obj[x].value + '</span>');
        }
        if (obj[x].key == "lname") {
            $('#input-lname').remove();
            $('.lname-container .span-value-medium').remove();
            $('.lname-container').append('<span class="span-value-medium">' + obj[x].value + '</span>');
        }
        if (obj[x].key == "add1") {
            $('#input-add1').remove();
            $('.add1-container .span-value-medium').remove();
            $('.add1-container').append('<span class="span-value-medium">' + obj[x].value + '</span>');
        }
        if (obj[x].key == "add2") {
            $('#input-add2').remove();
            $('.add2-container .span-value-medium').remove();
            $('.add2-container').append('<span class="span-value-medium">' + obj[x].value + '</span>');
        }
        if (obj[x].key == "state") {
            $('#state').remove();
            $('.state-container .span-value-medium').remove();
            $('.state-container').append('<span class="span-value-medium" id="state">' + obj[x].value + '</span>');
        }
        if (obj[x].key == "city") {

            $('#city').remove();
            $('.city-container .span-value-medium').remove();
            $('.city-container').append('<span class="span-value-medium" id="city">' + obj[x].value + '</span>');
        }
        if (obj[x].key == "contact") {
            $('#input-contact').remove();
            $('.contact-container .span-value-medium').remove();
            $('.contact-container').append('<span class="span-value-medium">' + obj[x].value + '</span>');
        }
        if (obj[x].key == "zip") {
            $('#input-zip').remove();
            $('.zip-container .span-value-medium').remove();
            $('.zip-container').append('<span class="span-value-medium">' + obj[x].value + '</span>');
        }
        if (obj[x].key == "email") {
            $('#input-email').remove();
            $('.email-container .span-value-medium').remove();
            $('.email-container').append('<span class="span-value-medium">' + obj[x].value + '</span>');
        }
        if (obj[x].key == "addEmail") {
            $('#input-add-email').remove();
            $('.input-addEmail').remove();
            $('.add-email-container .span-value-medium').remove();
            $('.add-email-container').append('<span class="span-value-medium">' + obj[x].value + '</span>');
        }
        $('#dob').remove();
        if (obj[x].key == "dob") {
            $('#input-dob').remove();
            $('.dob-outer-container .dob-container').html('<span class="span-value-medium">' + obj[x].value + '</span>');
        }
        if (obj[x].key == "gender") {
            $('.editGen').addClass('hide');
            $('.gender-container .span-value-medium').removeClass('hide');
            $('#gen').html(obj[x].value);
        }
        if (obj[x].key == "currentpass") {
            $('#input-currentpass').remove();
            $('.currentpass-container .span-value-medium').remove();
            $('.currentpass-container').append('<span class="span-value-medium">******</span>');
        }
        if (obj[x].key == "newpass") {
            $('#input-newpass').remove();
            $('.newpass-container .span-value-medium').remove();
            $('.newpass-container').append('<span class="span-value-medium"></span>');
        }
        if (obj[x].key == "confpass") {
            $('#input-confpass').remove();
            $('.confpass-container .span-value-medium').remove();
            $('.confpass-container').append('<span class="span-value-medium"></span>');
        }
    }
    $('#edit-profile-edited').attr('id', 'edit-profile');
    $('.update-button-container').remove();
    $('.profile-update-error').remove();
}
function cancelEdit() {
    $(document).on('click', '.cancel-edit-confirm', function() {
        $('#confirmCancelModal').modal('hide');
        renderProfileValues();
    });
}
function getAge(date) {
    var now = new Date();
    var diff = now.getTime() - date.getTime();
    return Math.floor(diff / (1000 * 60 * 60 * 24 * 365.25));

}
function calculateAge() {
    $(document).on('click', '.dob-outer-container .editable-submit ', function() {
        date = ($('.dob-outer-container .input-medium').datepicker("getDate"));
        userAge = (getAge(date));
        $('.age-container .span-value-medium').html(userAge);

        if (userAge >= 18) {
            $('.email-outer-container .span-heading-medium').html('<span style="color:#FF0000;">*</span>Email');
            $('.add-email-outer-container').addClass('disabled');
            $('.add-email-outer-container').hide();
            $('.row add-email-outer-container').hide();
        } else {

            $('.email-outer-container .span-heading-medium').html('<span style="color:#FF0000;">*</span>Email');

            $('.row add-email-outer-container').show();
            $('.add-email-outer-container').removeClass('disabled');
            $('.add-email-outer-container').show();
            if ($('#edit-profile-edited').length) {
                if (!$('#input-add-email').length) {
                    $('.add-email-outer-container .add-email-container').append('<input type="text" class="input" id="input-add-email" value=""/>');
                }
            }
        }
    });

}
function updateProfile() {
    $(document).on('click', '.confirmUpdateProfile', function() {
        $('#confirmUpdateModal').modal('hide');
        $('#user-profile .profile-update-error').remove();
        var fname = $('#input-fname').val();
        var lname = $('#input-lname').val();
        var street1 = $('#input-add1').val();
        var street2 = $('#input-add2').val();
        var city = ($('#citydd').length > 0) ? $('#citydd').val() : $('#city').html();
        //var state = $('#state').select2('data').text;
        var state = $('#state').val();
        var zip = $('#input-zip').val();
        var contact = $('#input-contact').val();
        var dob = $('#dob').html();
        var email = $('#input-email').val();
        var gender = $('[name=genradio]:checked').val();
        if (fname.replace(/&nbsp;/gi, '').length == 0) {
            $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Please enter First Name.</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if (lname.replace(/&nbsp;/gi, '').length == 0) {
            $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Please enter Last Name.</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if (street1.replace(/&nbsp;/gi, '').length == 0) {
            $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Please enter Address.</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ((state == "&nbsp;&nbsp;&nbsp;&nbsp;") || (state.length == 0)) {
            $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Please select State.</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if ((city == "&nbsp;&nbsp;&nbsp;&nbsp;") || (city.length == 0)) {
            $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Please select City.</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }

        if (zip.replace(/&nbsp;/gi, '').length == 0) {
            $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Please select Zip.</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if (!validateZip(zip)) {
            $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Please enter valid Zip.</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if (contact.replace(/&nbsp;/gi, '').length == 0) {
            $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Please Enter Contact Number.</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if (contact.length < 7 || contact.length > 10) {
            $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Contact number should be of 7 to 10 digits.</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if (!validatePhone(contact)) {
            $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Contact number should be numeric.</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if (dob.replace(/&nbsp;/gi, '').length == 0) {
            $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Please Enter Date of Birth.</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        if (gender === undefined) {
            $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Please Select Gender.</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        
        
        var curpassword = $('#present_pass').val();
        var cpwd = $('#input-currentpass').val();
        var npwd = $('#input-newpass').val();
        var cnfpwd = $('#input-confpass').val();

        if (cpwd != '') {
            if (cpwd != curpassword) {
               
                $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Wrong current Password.</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
            }
            if (npwd == '') {
               
                $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Please enter new password.</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
            }
        }
        if (npwd != '' && npwd.length < 6) {
            
            $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Password must be at least 6 characters long</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }

        if (npwd != '' && npwd != cnfpwd) {
          
            $('.profile-update-error').remove();
            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $newDataContainer.append('<span>Please enter same password again.</span>');
            $('#user-profile .user-profile-data-container').before($newDataContainer);
            $("html, body").animate({scrollTop: 0}, "slow");
            return false;
        }
        
        
        
        
        
        
        
        age = userAge;
        if (age >= 18) {
            if (email.length > 0) {
                if (validateEmail(email)) {
                    if (zip.length == 4 || zip.length == 5 || zip.length == 6) {
                        if (!xhrUpdateProfile) {
                            xhrUpdateProfile = $.ajax({
                                type: 'POST',
                                url: '/buzzydoc/editprofile/',
                                dataType: 'json',
                                data: {
                                    user_id: $('#user-id').val(),
                                    first_name: fname,
                                    last_name: lname,
                                    email: email,
                                    dob: dob,
                                    phone: contact,
                                    street1: street1,
                                    street2: street2,
                                    city: city,
                                    state: state,
                                    postal_code: zip,
                                    gender: gender,
                                    new_password:npwd
                                },
                                beforeSend: function() {
                                    $('#backdrop').show();
                                },
                                success: function(r) {
                                    $('.input-error').remove();
                                    $('.profile-update-error').remove();
                                    if (r.success) {

                                        $('.state-container').html('<span id="statedd" class="span-value-medium">' + r.state + '</span>');
                                        $('.city-container').html('<span id="city" class="span-value-medium">' + r.city + '</span>');
                                        for (x in obj) {
                                            if (obj[x].key == "fname") {
                                                obj[x].value = fname;

                                            }
                                            if (obj[x].key == "lname") {
                                                obj[x].value = lname;
                                            }
                                            if (obj[x].key == "add1") {
                                                obj[x].value = street1;
                                            }
                                            if (obj[x].key == "add2") {
                                                obj[x].value = street2;
                                            }
                                            if (obj[x].key == "state") {
                                                obj[x].value = r.state;
                                            }
                                            if (obj[x].key == "city") {
                                                obj[x].value = city;
                                            }
                                            if (obj[x].key == "contact") {
                                                obj[x].value = contact;
                                            }
                                            if (obj[x].key == "zip") {
                                                obj[x].value = zip;
                                            }
                                            if (obj[x].key == "email") {
                                                obj[x].value = email;
                                            }
                                            $('#dob').remove();
                                            if (obj[x].key == "dob") {
                                                obj[x].value = dob;
                                            }
                                            if (obj[x].key == "gender") {
                                                obj[x].value = gender;
                                            }
                                            if (obj[x].key == "currentpass") {
                                                obj[x].value = '******';
                                            }
                                            if (obj[x].key == "newpass") {
                                                obj[x].value = '';
                                            }
                                            if (obj[x].key == "confpass") {
                                                obj[x].value = '';
                                            }
                                        }
                                        renderProfileValues();
                                        var $newDataContainer = $('<div class="profile-update-error alert alert-success alert-dismissible text-center" role="alert"></div>');
                                        $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                                        $newDataContainer.append('<span>' + r.data + '</span>');
                                        $('#user-profile .user-profile-data-container').before($newDataContainer);
                                        $("html, body").animate({scrollTop: 0}, "slow");
                                    } else {
                                        $('#gen').html(gender);
                                        $('.input-error').remove();
                                        renderProfileValues();
                                        var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                                        $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                                        $newDataContainer.append('<span>' + r.data + '</span>');
                                        $('#user-profile .user-profile-data-container').before($newDataContainer);
                                        $("html, body").animate({scrollTop: 0}, "slow");
                                    }
                                    xhrUpdateProfile = false;
                                    $('#backdrop').hide();
                                }
                            });
                        }
                    } else {
                        var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
                        $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                        $newDataContainer.append('<span>Zip code should be of 4 to 6 characters.</span>');
                        $('#user-profile .user-profile-data-container').before($newDataContainer);
                        $("html, body").animate({scrollTop: 0}, "slow");
                        return false;
                    }
                } else {
                    var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
                    $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                    $newDataContainer.append('<span>Invalid Email Address.</span>');
                    $('#user-profile .user-profile-data-container').before($newDataContainer);
                    $("html, body").animate({scrollTop: 0}, "slow");
                    return false;
                }

            } else {
                var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
                $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                $newDataContainer.append('<span>Please enter Email Address.</span>');
                $('#user-profile .user-profile-data-container').before($newDataContainer);
                $("html, body").animate({scrollTop: 0}, "slow");
                return false;
            }
        } else {
            addEmail = $('#input-add-email').val();
            if (email.length > 0) {
                if (validateEmail(email)) {
                    if ($('#input-add-email').length > 0) {
                        if (addEmail.replace(/&nbsp;/gi, '').length == 0) {

                            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
                            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                            $newDataContainer.append('<span>Please enter Username.</span>');
                            $('#user-profile .user-profile-data-container').before($newDataContainer);
                            $("html, body").animate({scrollTop: 0}, "slow");
                            return false;

                        }
                    }
                    if ($('#input-add-email').length > 0) {
                        if (addEmail == email) {

                            var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
                            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                            $newDataContainer.append('<span>Email and Username should be different.</span>');
                            $('#user-profile .user-profile-data-container').before($newDataContainer);
                            $("html, body").animate({scrollTop: 0}, "slow");
                            return false;

                        }
                    }
                    if (zip.length == 4 || zip.length == 5 || zip.length == 6) {
                        if (!xhrUpdateProfile) {
                            xhrUpdateProfile = $.ajax({
                                type: 'POST',
                                url: '/buzzydoc/editprofile/',
                                dataType: 'json',
                                data: {
                                    user_id: $('#user-id').val(),
                                    first_name: fname,
                                    last_name: lname,
                                    email: email,
                                    parents_email: addEmail,
                                    dob: dob,
                                    phone: contact,
                                    street1: street1,
                                    street2: street2,
                                    city: city,
                                    state: state,
                                    postal_code: zip,
                                    gender: gender,
                                    new_password:npwd
                                },
                                beforeSend: function() {
                                    $('#backdrop').show();
                                },
                                success: function(r) {


                                    $('.input-error').remove();
                                    $('.profile-update-error').remove();
                                    if (r.success) {

                                        $('.state-container').html('<span id="statedd" class="span-value-medium">' + r.state + '</span>');
                                        $('.city-container').html('<span id="city" class="span-value-medium">' + r.city + '</span>');
                                        for (x in obj) {
                                            if (obj[x].key == "fname") {
                                                obj[x].value = fname;
                                                $("#usernametext").text(fname);
                                                $("#maintextname").text(fname);
                                            }
                                            if (obj[x].key == "lname") {
                                                obj[x].value = lname;
                                            }
                                            if (obj[x].key == "add1") {
                                                obj[x].value = street1;
                                            }
                                            if (obj[x].key == "add2") {
                                                obj[x].value = street2;
                                            }
                                            if (obj[x].key == "state") {
                                                obj[x].value = r.state;
                                            }
                                            if (obj[x].key == "city") {
                                                obj[x].value = city;
                                            }
                                            if (obj[x].key == "contact") {
                                                obj[x].value = contact;
                                            }
                                            if (obj[x].key == "zip") {
                                                obj[x].value = zip;
                                            }
                                            if (obj[x].key == "email") {
                                                obj[x].value = email;
                                            }
                                            if (obj[x].key == "addEmail") {
                                                obj[x].value = addEmail;
                                            }
                                            $('#dob').remove();
                                            if (obj[x].key == "dob") {
                                                obj[x].value = dob;
                                            }
                                            if (obj[x].key == "gender") {
                                                obj[x].value = gender;
                                            }
                                            if (obj[x].key == "currentpass") {
                                                obj[x].value = '******';
                                            }
                                            if (obj[x].key == "newpass") {
                                                obj[x].value = '';
                                            }
                                            if (obj[x].key == "confpass") {
                                                obj[x].value = '';
                                            }
                                        }
                                        renderProfileValues();
                                        var $newDataContainer = $('<div class="profile-update-error alert alert-success alert-dismissible text-center" role="alert"></div>');
                                        $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                                        $newDataContainer.append('<span>' + r.data + '</span>');
                                        $('#user-profile .user-profile-data-container').before($newDataContainer);
                                        $("html, body").animate({scrollTop: 0}, "slow");
                                    } else {
                                        $('#gen').html(gender);
                                        $('.input-error').remove();
                                        renderProfileValues();
                                        var $newDataContainer = $('<div class="profile-update-error alert alert-warning alert-dismissible text-center" role="alert"></div>');
                                        $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                                        $newDataContainer.append('<span>' + r.data + '</span>');
                                        $('#user-profile .user-profile-data-container').before($newDataContainer);
                                        $("html, body").animate({scrollTop: 0}, "slow");
                                    }
                                    xhrUpdateProfile = false;
                                    $('#backdrop').hide();
                                }
                            });
                        }
                    } else {
                        var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
                        $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                        $newDataContainer.append('<span>Zip code should be of 4 to 6 characters.</span>');
                        $('#user-profile .user-profile-data-container').before($newDataContainer);
                        $("html, body").animate({scrollTop: 0}, "slow");
                        return false;
                    }
                } else {
                    var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
                    $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                    $newDataContainer.append('<span>Invalid Email Address.</span>');
                    $('#user-profile .user-profile-data-container').before($newDataContainer);
                    $("html, body").animate({scrollTop: 0}, "slow");
                    return false;
                }

            } else {
                var $newDataContainer = $('<div class="profile-update-error alert alert-danger alert-dismissible text-center" role="alert"></div>');
                $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                $newDataContainer.append('<span>Please enter Email Address.</span>');
                $('#user-profile .user-profile-data-container').before($newDataContainer);
                $("html, body").animate({scrollTop: 0}, "slow");
                return false;
            }
        }
    });
}
function getObjectId(objects, key) {
    for (x in objects) {
        if (objects[x].text == key) {
            return (objects[x].id);
        }
    }
}


/************************************** Main top menu toggle **************************************/

$('.menu-top-btn').on('click', function() {
    var overlay = $('<div class="overlay-div" style="background:#333; opacity: 0.5; width: 100%; position: absolute; top: 53px; left: 0; min-height: 100% !important;"></div>');

    $('.wrap-dd').slideToggle();
    $('html').toggleClass('scroll-none');

    if (($('.wrap-dd').css('display') == 'block')) {
        if (($('.overlay-div').length < 1)) {
            $('body').append(overlay);
        }
    }

    /*
     $(overlay).click(function (){ console.log('second');
     $(".wrap-dd").slideUp();
     $('.overlay-div').remove();
     }); */
});

$(".wrap-dd").on('click', function(e) {
    //e.stopPropagation();
});

$(document).click(function(e) {
    var container = $(".menu-top-btn"),
            windowWidth = $(window).width();

    if (windowWidth < 767 || 1) {
        var button = $("#navbar .navbar-toggle");
        if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) {
            $(".wrap-dd").slideUp();
            $('html').removeClass('scroll-none');
            if (button.attr("aria-expanded") == "true") {
                //$("#navbar .navbar-toggle").click();
                $(".menu-top-btn").click();
            }

        } else if (($('.wrap-dd').css('display') == 'block') && (button.attr("aria-expanded") == "false")) {

            $(".wrap-dd").slideUp();
            $('html').removeClass('scroll-none');
            $('.overlay-div').remove();
        }
    }
});


/************************************** End Main top menu toggle **************************************/
$(document).on("click", "#dob", function() {
    $(".input-medium").attr('disabled', 'true');
    $(document).on("click", ".input-medium", function() {
        $(".input-medium").attr('disabled', 'true');
    });
});

