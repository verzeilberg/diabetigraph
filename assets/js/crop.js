import Cropper from 'cropperjs/dist/cropper';
import axios from 'axios';
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router';
import Routes from './routes.json';

Routing.setRoutingData(Routes);

$(document).ready(function () {
    let cropper;


    var preview = document.getElementById('avater');
    var file_input = document.getElementById('user_profile_form_Image');

    $("#user_profile_form_Image").change(function () {
        let file = file_input.files[0];
        let reader = new FileReader();

        reader.addEventListener('load', function (event) {
            preview.src = reader.result;
        }, false)

        if (file) {
            reader.readAsDataURL(file)
        }


    });

    preview.addEventListener('load', function (event) {
        cropper = new Cropper(preview, {
            aspectRatio: 1 / 1,
            scalable: false,
            cropBoxResizable: false,
            viewMode: 3
        })
    })

    let form = document.getElementById('user_profile_form');
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        cropper.getCroppedCanvas({
            maxHeight: 400,
            maxWidth: 400
        }).toBlob(function (blob) {
            sendBlob(blob);
        })
    })

    function sendBlob(blob) {

        let url = Routing.generate('image');
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
                    form.submit();
                }
            })
            .catch((error) => {
                console.log(error);
            })
    }
});