let     streaming = false,
            video        = document.querySelector('video'),
            canvas       = document.querySelector('canvas'),
            save         = document.querySelector('#save'),
            photo        = document.querySelector('#picture'),
            button       = document.querySelector('button'),
            pic_input    = document.querySelector('#pic'),
            filter_input = document.querySelector('#filter'),
            x = document.querySelector('#x'),
            y = document.querySelector('#y'),
            width = 640,
            height = 0;

    const     ghost = document.querySelector('#ghost'),
            willface = document.querySelector('#willface'),
            glasses = document.querySelector('#glasses');
            hair = document.querySelector('#hair');

    let     filtre = [glasses, willface, ghost];
    let     selected = -1;

    let cust_pic = document.getElementById('cust_pic');
    let gal_pics = document.getElementsByClassName("gal-pics");
    document.querySelector('#login');
    var xhr = new XMLHttpRequest();

    navigator.getMedia = ( navigator.getUserMedia ||
                        navigator.webkitGetUserMedia ||
                        navigator.mozGetUserMedia ||
                        navigator.msGetUserMedia);

    navigator.getMedia(
    {
     video: true,
     audio: false
    },
    function(stream) {
     if (navigator.mozGetUserMedia) {
       video.mozSrcObject = stream;
     } else {
       var vendorURL = window.URL || window.webkitURL;
       video.src = vendorURL.createObjectURL(stream);
     }
     video.play();
    },
    function(err) {

    }
    );

    cust_pic.addEventListener('change', (e) => {
        let files = cust_pic.files;
        files = files[0];

        let reader = new FileReader();
        reader.onload = (files) => {
            photo.file = files;
            photo.src = files.target.result;
            save.file = files;
            save.src = files.target.result;

            let base_image = new Image();
            base_image.src = files.target.result;
            base_image.onload = function(){
                canvas.getContext('2d').drawImage(base_image, 0, 0, width, height);
                save.getContext('2d').drawImage(base_image, 0, 0, width, height);
                pic_input.value = canvas.toDataURL('image/jpg');
            }
            photo.style.display = "inline";
        };
        reader.readAsDataURL(files);
    }, false);

    video.addEventListener('canplay', function(ev){
    if (!streaming) {
     height = video.videoHeight / (video.videoWidth/width);
     video.setAttribute('width', width);
     video.setAttribute('height', height);
     canvas.setAttribute('width', width);
     canvas.setAttribute('height', height);
     save.setAttribute('width', width);
     save.setAttribute('height', height);
     streaming = true;
    }
    }, false);

    function getValue(v) {
        return v.value;
    }

    function takepicture() {
        if (streaming === true) {
        canvas.width = width;
        canvas.height = height;
        canvas.getContext('2d').drawImage(video, 0, 0, width, height);
        save.getContext('2d').drawImage(video, 0, 0, width, height);
        var data = canvas.toDataURL('image/jpg');
        photo.setAttribute('src', data);
        photo.style.display = "inline";

        pic_input.value = data;
        canvas.getContext('2d').save();
        }
    }

    willface.addEventListener('click', (ev) => {
        selected = 1;
        canvas.getContext('2d').drawImage(save, 0, 0, width, height);
        canvas.getContext('2d').drawImage(willface, getValue(x), getValue(y));
        var data = canvas.toDataURL('image/jpg');
        filter.value = "filtre/willface.png";
        document.querySelector('#login').style.display = "inline";
        document.querySelector('#send-container').style.display = "inline";
        if (photo.src != "")
            document.querySelector('#send-container').style.display = "inline";
        photo.setAttribute('src', data);
        willface.style = "box-shadow: 2px 2px 15px black; width: 30%";
        ghost.style = "box-shadow: 0px black; width: 30%";
        glasses.style = "box-shadow: 0px black; width: 30%";
        hair.style = "box-shadow: 0px black; width: 30%";
        ev.preventDefault();
    }, false);

    ghost.addEventListener('click', (ev) => {
        selected = 2;
        canvas.getContext('2d').drawImage(save, 0, 0, width, height);
        canvas.getContext('2d').drawImage(ghost, getValue(x), getValue(y));
        var data = canvas.toDataURL('image/jpg');
        filter.value = "filtre/ghost.png";
        document.querySelector('#login').style.display = "inline";
        document.querySelector('#send-container').style.display = "inline";
        if (photo.src != "")
            document.querySelector('#send-container').style.display = "inline";
        photo.setAttribute('src', data);
        ghost.style = "box-shadow: 2px 2px 15px black; width: 30%";
        glasses.style = "box-shadow: 0px black; width: 30%";
        willface.style = "box-shadow: 0px black; width: 30%";
        hair.style = "box-shadow: 0px black; width: 30%";
        ev.preventDefault();
    }, false);

    x.addEventListener('input', (ev) => { ev.preventDefault(); filtre[selected].click();},false);
    y.addEventListener('input', (ev) => { ev.preventDefault(); filtre[selected].click();},false);

    glasses.addEventListener('click', (ev) => {
        selected = 0;
        canvas.getContext('2d').drawImage(save, 0, 0, width, height);
        canvas.getContext('2d').drawImage(glasses, getValue(x), getValue(y));
        var data = canvas.toDataURL('image/jpg');
        filter.value = "filtre/glasses.png";
        document.querySelector('#login').style.display = "inline";
        if (photo.src != "")
            document.querySelector('#send-container').style.display = "inline";
        photo.setAttribute('src', data);
        glasses.style = "box-shadow: 2px 2px 15px black; width: 30%";
        ghost.style = "box-shadow: 0px black; width: 30%";
        willface.style = "box-shadow: 0px black; width: 30%";
        hair.style = "box-shadow: 0px black; width: 30%";
        ev.preventDefault();
    }, false);

    video.addEventListener('click', (ev) => {
     takepicture();
    ev.preventDefault();
    }, false);

    function deletePics(data) {
        if (window.confirm("Are you sure that you want to delete this picture ?") === true) {
        xhr.open('GET', 'delete_img.php?id=' + data.id, true);
        xhr.send();
        xhr.onload = () => {
            if (xhr.status === 200 && xhr.readyState === 4) {
                if (xhr.responseText === "OK") {
                    data.style.display = "none";
                }
            }
        }
        }
    }

    for (let i = 0; i < gal_pics.length; i++) {
        gal_pics[i].addEventListener('click', (ev) => { ev.preventDefault(); deletePics(gal_pics[i]); }, false);
    }