var input = document.querySelector('#inputImage');
var icon = document.querySelector('#icon');
input.addEventListener('change', changeDisplay);

function changeDisplay(){
    var files = input.files;
    if(files.length == 0){
        return
    }
    icon.src = URL.createObjectURL(files[0]);
}
