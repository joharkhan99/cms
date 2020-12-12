$(document).ready(() => {

  // Editor
  ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => {
      console.log(error.stack);
    });

  // select all boxes
  $('#selectAllBoxes').click(function () {

    //check if main checkbox is checked
    if (this.checked) {
      $('.checkBoxes').each(function () {
        this.checked = true;                //mark all boxes checked
      });
    }
    else {
      $('.checkBoxes').each(function () {
        this.checked = false;
      });
    }
  });

  //loader and image
  var div_box = "<div id='load-screen'> <div id='loading'></div> </div>";
  $("body").prepend(div_box);       //append to body

  $('#load-screen').delay(700).fadeOut(600, () => {
    $(this).remove();     //remove image and background
  })

});


//below function will send get request to functions. from tehre it will get online users count and will add that count to span elem in admin_nav

function loadUsersOnline() {
  $.get("functions.php?onlineusers=result", function (data) {
    $('.usersonline').text(data);
  });
}

//call after every 500 ms
setInterval(() => {
  loadUsersOnline();
}, 500);



