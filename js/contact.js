var required = document.querySelectorAll('[required]');
var length = required.length;

var alert = document.getElementById("required-alert");
var error = document.getElementById("email-error");
var sent = document.getElementById("email-sent");

document.getElementById("contact-form").onsubmit = function(event) {
  for (var i = 0; i < length; i++) {
    var field = required[i];
    var value = field.value.trim();

    if (value === "") {
      alert.style.display = "block";
      return false;
    }
  }

  alert.style.display = "none";
  error.style.display = "none";
  sent.style.display = "none";

  var contact_form = $("#contact-form");

  $.ajax({
      type: contact_form.attr("method"),
      url: contact_form.attr("action"),
      data: contact_form.serialize(),
      success: function(data) {
        if (data.success) {
          sent.style.display = 'block';
          contact_form.clear();
        }
        else {
          error.style.display = 'block';
        }
      }
  });

  return false;
}
