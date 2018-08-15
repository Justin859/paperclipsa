@extends('layouts.app')

@section('styles')

@include('includes.user_profile_styles')
<style>
    h2, p
    {
        color: #ffffff;
    }
    .fa-plug, .fa-trash, .fa-edit, .fa-toggle-on
    , .fa-toggle-off {
        font-size: 24px;
    }

    .fa-toggle-on
    {
        color: #228B22;
    }
    .table {
        margin: auto;
    }

    td, th {
        vertical-align: middle !important;
    }

    .pagination
    {
        font-size: 24px;
    }
    .fa-step-forward, .fa-step-backward, .fa-fast-forward, .fa-fast-backward 
    {
        color: rgba(208, 0, 0);
    }

    .pagination-number
    {
        color: #FFFFFF !important;
        background-color: rgba(208, 0, 0) !important;
    }

    .pagination-skip, .pagination-number-active
    {
        background-color: #181818 !important;
    }
</style>
@endsection

@section('content')

<div class="container">

    <div class="row">
        
    <div class="col-12 col-md-9 order-md-2">
        <br />
        <h2 class="pt-2"> Create A New Venue </h2><hr />
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="/user-profile/superuser/venue/save" id="edit-form" method="post" enctype="multipart/form-data" novalidate>
            @csrf
            <input type="hidden" name="venue_id" />
            <label style="color:#FFCC00">Venue Page Details</label>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="venueName">Name</label>
                    <input type="text" name="name" onkeyup="not_empty(this);" onchange="not_empty(this);"  id="venueName" class="form-control" required />
                    <div class="invalid-tooltip">
                        Please provide a name for the venue.
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="venueName">Venue Type</label>
                    <select name="venue_type" class="form-control">
                        <option value="indoor_soccer" selected="selected">Indoor Soccer</option>
                        <option value="squash">Squash</option>
                        <option value="soccer_school">Soccer School</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="venueDescription">Description</label>
                <div class="input-group mb-3">
                <textarea name="description" onkeyup="not_empty(this);" id="venueDescription" class="form-control" maxlength="1500" rows="4" required></textarea>
                <div class="invalid-tooltip">
                    Please provide a description for the venue.
                </div>
                </div>
            </div>
            <div class="form-group">
                <label for="venuePhoneNumbers">Contact Number(s)</label>
                <div id="phone_numbers">
                <div class="input-group mb-3" id="venuePhoneNumberEl_1">
                    <input type="tel" onkeyup="valid_phone_number(this);" placeholder="123 456 7890" pattern="[0-9]{3} [0-9]{3} [0-9]{4}" name="phone_number[]" id="venuePhoneNumber_1" class="form-control" required />
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-danger" type="button" onclick="remove_number(1);" id="button-addon2">Remove</button>
                    </div>
                    <div class="invalid-tooltip">
                        Please provide a valid phone number. Ex. 011 123 4567.
                    </div>
                </div>
                </div>
                <div class="validation-message-ports">
                    <div class="alert alert-danger" style="display:none;" role="alert">
                        <strong>Error</strong> Please be sure that all fields in this section are not left blank.
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group mb-3 justify-content-end">
                    <button type="button" class="btn btn-outline-info" onclick="add_number();" type="button" id="button-addon2">Add &nbsp;&nbsp;<i class="fas fa-plus"></i></button>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="venueYoutube">Youtube Video</label>
                    <input type="url" id="venueYoutube" name="intro_vid_url" onkeyup="valid_url(this);" placeholder="ex. https://www.youtube.com/watch?v=qLUZ9-F9tXg" class="form-control">
                    <div class="invalid-tooltip">
                        Please provide a valid url. Ex. https://www.youtube.com/watch?v=WI7RpqGdxjs
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="venueTwitter">Twitter Account</label>
                    <input type="url" id="venueTwitter" name="twitter_url" onkeyup="valid_url(this);" placeholder="ex. https://twitter.com/paperclipsa" class="form-control">
                    <div class="invalid-tooltip">
                        Please provide a valid url. Ex. https://www.google.co.za
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="venueFacebook">Facebook Account</label>
                    <input type="url" id="venueFacebook" name="fb_url" onkeyup="valid_url(this);" placeholder="ex. https://www.facebook.com/paperclipsa"  class="form-control">
                    <div class="invalid-tooltip">
                        Please provide a valid url. Ex. https://www.google.co.za
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="venueWebsite">Website</label>
                    <input type="url" id="venueWebsite" name="web_url" onkeyup="valid_url(this);" placeholder="ex. http://www.paperclipsa.co.za/"  class="form-control">
                    <div class="invalid-tooltip">
                        Please provide a valid url. Ex. https://www.google.co.za
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="venueBanner">Page Banner (required)</label>
                <div class="input-group mb-3">
                <input type="file" name="banner_img" class="form-control-file" id="banner_img" onchange="has_img(this);" style="color:#ffffff;" id="exampleFormControlFile1">
                <div class="invalid-tooltip">
                    Please provide a banner image
                </div>
                </div>
            </div>
            <div class="form-group">
                <label for="venueLogo">Page Logo (required)</label>
                <div class="input-group mb-3">
                <input type="file" name="logo_img" class="form-control-file" id="logo_img" onchange="has_img(this);" style="color:#ffffff;" id="exampleFormControlFile2">
                <div class="invalid-tooltip">
                    Please provide a logo image
                </div>
                </div>
            </div>
            <hr />
            <label style="color:#FFCC00">RTSP Camera Connection Details</label>
            <div class="form-row">
                <div class="col-md-4 mb-3">
                <label for="username">Username</label>
                <input type="text" onkeyup="not_empty(this);" onchange="not_empty(this);" class="form-control" name="username" id="username" placeholder="username"  required />
                <div class="invalid-tooltip">
                    Please provide a username
                </div>
                </div>
                <div class="col-md-4 mb-3">
                <label for="password">Password</label>
                <input type="text" onkeyup="not_empty(this);" onchange="not_empty(this);" class="form-control" name="password" id="password" placeholder="password"  required />
                <div class="invalid-tooltip">
                    Please provide a password
                </div>
                </div>
                <div class="col-md-4 mb-3">
                <label for="password">IP Address</label>
                <input type="text" onkeyup="not_empty(this);" onchange="not_empty(this);" class="form-control" name="venue_ip" id="ip_address" placeholder="ip address"  required />
                <div class="invalid-tooltip">
                    Please provide an ip address
                </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 mb-3">
                <input type="text" onkeyup="not_empty(this);" onchange="not_empty(this);"  class="form-control" name="wow_app_name" id="wow_app_name" placeholder="Wowza Application Name Ex. PAPERCLIP_SA_INDOOR"  required />
                <div class="invalid-tooltip">
                    Please provide a Wowza Application Name.
                </div>
                </div>
            </div>
                <label>Area camera port names and port numbers</label>
                <div id="port_numbers">
                
                <div class="form-row" style="border: 1px solid;" id="venuePortNameEl_1">
                    <div class="form-group col-md-6">
                        <div class="input-group mt-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupNameAddon_1">name</span>
                            </div>
                            <input type="text" onkeyup="not_empty(this);" onchange="not_empty(this);"  name="port_name[]" class="form-control"  required />
                            <div class="invalid-tooltip">
                                Please provide a name.
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="input-group mt-3" id="venuePortNumberEl_1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon01">port</span>
                            </div>
                            <input type="number" onkeyup="not_empty(this);" onchange="not_empty(this);"  placeholder="ex. 555" name="port_number[]"  id="venuePortNumber_1" class="form-control" required />
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-danger" type="button" onclick="remove_port(1);" id="button-addon2">Remove</button>
                            </div>
                            <div class="invalid-tooltip">
                                Please provide a port number.
                            </div>
                        </div>
                    </div>
                </div>
                
                </div>
                <div class="validation-message-ports">
                    <div class="alert alert-danger" style="display:none;" role="alert">
                        <strong>Error</strong> Please be sure that all fields in this section are not left blank.
                    </div>
                </div>
                <br />
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="input-group mb-3 justify-content-end">
                        <button type="button" class="btn btn-outline-info" onclick="add_port();" type="button" id="button-addon2">Add &nbsp;&nbsp;<i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="input-group mb-3 justify-content-end">
                        <button type="submit" class="btn btn-lg btn-outline-warning" type="button" id="button-addon2">Save Changes &nbsp;&nbsp;<i class="far fa-save"></i></button>
                        </div>
                    </div>
                </div>
        </form>
    </div>
    
    @include('includes.user_side_panel')<!-- side panel -->
    </div>
</div>

@endsection

@section('modal')

@endsection

@section('scripts')
<script>

var add_number = function()
{
    var current_numbers = 0;

    $("input[name='phone_number[]'").each(function() {
        current_numbers += 1;
    });

    var key = current_numbers + 1;

    var htmlToAdd = '<div class="input-group mb-3" id="venuePhoneNumberEl_'+key+'">'
                  +     '<input type="tel" onkeyup="valid_phone_number(this);" name="phone_number[]" placeholder="ex. 123 456 7890" pattern="[0-9]{3} [0-9]{3} [0-9]{4}" id="venuePhoneNumber_'+key+'" class="form-control" required />'
                  +     '<div class="input-group-append">'
                  +         '<button type="button" onclick="remove_number('+key+')" class="btn btn-outline-danger" type="button" id="button-addon2">Remove</button>'
                  +     '</div>'
                  +     '<div class="invalid-tooltip">'
                  +      'Please provide a phone number.'
                  +      '</div>'
                  + '</div>'

    $(htmlToAdd).appendTo("#phone_numbers");
};

var add_port = function()
{
    current_ports = 0;

    $("input[name='port_number[]'").each(function() {
        current_ports += 1;
    });

    var key = current_ports + 1;

    var htmlToAdd = '<div class="form-row" style="border: 1px solid;" id="venuePortNameEl_'+key+'">'
    +     '<div class="form-group col-md-6">'
    +         '<div class="input-group mt-3">'
    +             '<div class="input-group-prepend">'
    +                 '<span class="input-group-text" id="inputGroupNameAddon_'+key+'">name</span>'
    +             '</div>'
    +             '<input type="text" onkeyup="not_empty(this);" onchange="not_empty(this);" name="port_name[]" class="form-control" placeholder="ex. Field Z" required />'
    +     '<div class="invalid-tooltip">'
    +      'Please provide a name.'
    +      '</div>'
    +         '</div>'
    +     '</div>'
    +     '<div class="form-group col-md-6">'
    +         '<div class="input-group mt-3" id="venuePortNumberEl_'+key+'">'
    +             '<div class="input-group-prepend">'
    +                 '<span class="input-group-text" id="inputGroupFileAddon01">port</span>'
    +             '</div>'
    +             '<input type="number" onkeyup="not_empty(this);" onchange="not_empty(this);" name="port_number[]" value="555" placeholder="ex. 555" id="venuePortNumber_'+key+'" class="form-control" required />'
    +             '<div class="input-group-append">'
    +                 '<button type="button" class="btn btn-outline-danger" type="button" onclick="remove_port('+key+');" id="button-addon2">Remove</button>'
    +             '</div>'
    +     '<div class="invalid-tooltip">'
    +      'Please provide a port number.'
    +      '</div>'
    +         '</div>'
    +     '</div>'
    + '</div>'
    $(htmlToAdd).appendTo("#port_numbers");

}

var remove_number = function(el_id)
{
    $("#venuePhoneNumberEl_" + el_id).remove();
}

var remove_port = function(el_id)
{
    $("#venuePortNameEl_" + el_id).remove();
}

var not_empty = function(el)
{
    if(!$(el).val())
    {
        if(!$(el).hasClass('is-invalid'))
        {
            $(el).addClass('is-invalid');
        }
        
    } else {
        if($(el).hasClass('is-invalid'))
        {
        $(el).removeClass('is-invalid');
        }
    }
}

var has_img = function(el)
{
    if(!$(el).val())
    {
        if(!$(el).hasClass('is-invalid'))
        {
            $(el).addClass('is-invalid');
            $(el).next().css("display", "block");
        }
        
    } else {
        if($(el).hasClass('is-invalid'))
        {
        $(el).removeClass('is-invalid');
        $(el).next().css("display", "none");
        }
    }
}

var valid_phone_number = function(el)
{
    var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;

    if(!$(el).val().match(phoneno))
    {
        if(!$(el).hasClass('is-invalid'))
        {
            $(el).addClass('is-invalid');
        }
    } else {
        if($(el).hasClass('is-invalid'))
        {
        $(el).removeClass('is-invalid');
        }
    }

}

var valid_url = function(el)
{
    function validateUrl(value) {
        return /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(value);
    }

    if(!validateUrl($(el).val()))
    {
        if(!$(el).hasClass('is-invalid'))
        {
            $(el).addClass('is-invalid');
        }
    } else {
        if($(el).hasClass('is-invalid'))
        {
        $(el).removeClass('is-invalid');
        }
    }

}

$(document).ready(function(){
    // validation for edit-form

    $('#edit-form').submit(function(event) {

        // inputs

        var name_el = $('#venueName');
        var description_el = $("#venueDescription");
        var banner_img_el = $("#banner_img");
        var logo_img_el = $('#logo_img');

        var port_number_el = $('input[name^=port_number]').map(function(idx, elem) {
            if(!$(elem).val())
            {
                $(elem).addClass('is-invalid');
                event.preventDefault();
                console.log('port_number_el');
            } else {
                $(elem).removeClass('is-invalid');
            }
        });
        var port_name_el = $('input[name^=port_name]').map(function(idx, elem) {
            if(!$(elem).val())
            {
                $(elem).addClass('is-invalid');
                event.preventDefault();
                console.log('port_name_el');
            } else {
                $(elem).removeClass('is-invalid');
            }
            
        });
        var phone_number_el = $('input[name^=phone_number]').map(function(idx, elem) {
            if(!$(elem).val())
            {
                $(elem).addClass('is-invalid');
                event.preventDefault();
                console.log('phone_number_el');
            } else {
                $(elem).removeClass('is-invalid');
            }
        });

        if(!$(name_el).val())
        {
            $(name_el).addClass('is-invalid');
            event.preventDefault();
            console.log('name_el');
        } else {
            $(name_el).removeClass('is-invalid');
        }

        if(!$(description_el).val())
        {
            $(description_el).addClass('is-invalid');
            event.preventDefault();
            console.log('description_el');
        } else {
            $(description_el).removeClass('is-invalid');

        }

        if(!$(banner_img_el).val())
        {
            $(banner_img_el).addClass('is-invalid');
            $(banner_img_el).next().css("display", "block")
            event.preventDefault();
            console.log('banner_img_el');

        } else {
            $(banner_img_el).removeClass('is-invalid');
            $(banner_img_el).next().css("display", "none");
        }

        if(!$(logo_img_el).val())
        {
            $(logo_img_el).addClass('is-invalid');
            $(logo_img_el).next().css("display", "block")
            event.preventDefault();
            console.log('logo_img_el');

        } else {
            $(logo_img_el).removeClass('is-invalid');
            $(logo_img_el).next().css("display", "none");
        }      

        var username_el = $('#username');
        var password_el = $('#password');
        var ip_address_el = $('#ip_address');
        var wow_app_name_el = $('#wow_app_name');

        if(!$(username_el).val())
        {
            $(username_el).addClass('is-invalid');
            event.preventDefault();
            console.log('username_el');
        } else {
            $(username_el).removeClass('is-invalid');

        }

        if(!$(password_el).val())
        {
            $(password_el).addClass('is-invalid');
            event.preventDefault();
            console.log('password_el');
        } else {
            $(password_el).removeClass('is-invalid');

        }

        if(!$(ip_address_el).val())
        {
            $(ip_address_el).addClass('is-invalid');
            event.preventDefault();
            console.log('ip_address_el');
        } else {
            $(ip_address_el).removeClass('is-invalid');

        }    

        if(!$(wow_app_name_el).val())
        {
            $(wow_app_name_el).addClass('is-invalid');
            event.preventDefault();
            console.log('wow_app_name_el');
        } else {
            $(wow_app_name_el).removeClass('is-invalid');
        }         

    });
});

</script>
@endsection
