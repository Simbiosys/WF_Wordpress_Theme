$(function() {
  var datepicker = $('.input-daterange').datepicker({
    format: "MM, yyyy",
    minViewMode: 1
  });

  var startField = document.getElementById("start");
  var endField = document.getElementById("end");

  var startHidden = document.querySelectorAll(".start-hidden");
  var endHidden = document.querySelectorAll(".end-hidden");

  start.onchange = function() {
    var start = this.value;

    if (!start)
      return;

    start = new Date('01 ' + start);

    var date = getDate(start);

    for (var i = 0; i < startHidden.length; i++)
      startHidden[i].value = date;
  }

  end.onchange = function() {
    var end = this.value;

    if (!end)
      return;

    end = new Date('01 ' + end);
    end.setMonth(end.getMonth() + 1);
    end.setDate(end.getDate() - 1);

    var date = getDate(end);

    for (var i = 0; i < endHidden.length; i++)
      endHidden[i].value = date;
  }

  function getDate(date) {
    return date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
  }
});
