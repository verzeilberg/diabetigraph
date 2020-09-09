import Cropper from 'cropperjs/dist/cropper';
import axios from 'axios';
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router';
import Routes from './routes.json';

Routing.setRoutingData(Routes);

$(document).ready(function () {
    let cropper;
    var preview = document.getElementById('avatar');
    var file_input = document.getElementById('user_profile_form_Image');
    cropper = new Cropper(preview,{
        responsive: true,
        maxHeight: 800,
        maxWidth: 800
    })

    $("#user_profile_form_Image").change(function () {
        let file = file_input.files[0];
        let reader = new FileReader();
        reader.addEventListener('load', function (event) {
            cropper.replace(reader.result);
        }, false)
        if (file) {
            reader.readAsDataURL(file)
        }
    });

    let form = document.getElementById('user_profile_form');
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        cropper.getCroppedCanvas({

        }).toBlob(function (blob) {
            sendBlob(blob);
        })
    })

    function sendBlob(blob) {
        let url = Routing.generate('image');
        let redirectUrl = Routing.generate('profile');
        let data = new FormData(form);
        data.append('file', blob);
        axios({
            method: 'post',
            url: url,
            data: data,
            headers: {'X-Requested-With': 'XmlHttpRequest'}
        })
            .then((response) => {
                if (response.data.result.error == null) {
                    window.location.replace(redirectUrl);
                }
            })
            .catch((error) => {
                console.log(error);
            })

    }

    /**
     * Rotate image left
     */
    $("#imageRotateLeft").click(function () {
        cropper.rotate(90);
    });

    /**
     * Rotat image right
     */
    $("#imageRotateRight").click(function () {
        cropper.rotate(-90);
    });

    /**
     * Flip image horizontal
     */
    $("#imageFlipHorizontal").click(function () {
        var scale = $(this).data('option');
        if(scale == 1) {
            $(this).data('option', '-1' )
        } else {
            $(this).data('option', '1' )
        }
        cropper.scaleX(scale);
    });

    /**
     * Flip image vertically
     */
    $("#imageFlipVertical").click(function () {
        var scale = $(this).data('option');
        if(scale == 1) {
            $(this).data('option', '-1' )
        } else {
            $(this).data('option', '1' )
        }
        cropper.scaleY(scale);
    });

});