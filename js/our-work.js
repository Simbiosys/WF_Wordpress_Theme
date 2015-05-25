var _width =  window.innerWidth;

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');

    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] == sParam) {
            return sParameterName[1];
        }
    }
}

function openIssue(active) {
  $(".issues-container").find("div.col-issue")
  $(".col-change").addClass("col-lg-3 col-md-3").removeClass("col-lg-4 col-md-4");
  $("#col-" + active + "").addClass("col-lg-6 col-md-6 col-issue-active").removeClass("col-lg-3 col-md-3");
  $("#col-" + active + " .issues-content-preview").fadeOut("fast");
  $("#cont-" + active + "").removeClass("hddn");
  $("#col-" + active + "").css("z-index","20");
  $("#cont-" + active + "").addClass('animated zoomIn');

  $("#cont-" + active + "").one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
    $(this).removeClass("animated zoomIn");
    if (_width <= 768) {
      $("html, body").animate({ scrollTop: $('#cont-'+ active +'').offset().top }, 500);
    }
  });
}

function closeIssue(close,active) {
  $("#cont-" + close + "").addClass('animated fadeOut');

  $("#cont-" + close + "").one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
    //remove animation class

    $("#col-" + close + "").removeClass("col-issue-active");

    $(this).removeClass("animated fadeOut");

    $(".col-change").addClass("col-lg-4 col-md-4").removeClass("col-lg-3 col-md-3");

    $("#col-" + close + " .issues-content-preview").fadeIn("slow");
    $("#cont-" + close + "").addClass("hddn");
    $("#col-" + close + "").css("z-index","0");

    if (active != 0) {
      openIssue(active);
    }
    else {
      if (_width <= 768) {
        $("html, body").animate({ scrollTop: $('#cont-'+ active +'').offset().top }, 500);
      }
    }
  });
}

$(document).ready(function() {
  window.onresize = function(event) {
    _width =  window.innerWidth;
  };
  //Carousel init
  $(".carousel-about-foundation").owlCarousel();

  var secc = getUrlParameter('gl');

  if(secc != undefined) {
    //This function is for avoid triggering the event before the handler is attached. On this case the solution is to use setTimeout:
    setTimeout(function() {
      $("a.btn-readm[data-id='" + secc + "']")[0].click();
      secc = undefined;
      },10);
  }

  $(".btn-readm").on("click", function(e) {
    e.stopPropagation();
    e.preventDefault();

    var close = $("#issues-container").find(".col-issue-active").attr("data-id");
    var open = $(this).attr("data-id");

    if (close == undefined) {
      openIssue(open);
    }
    else{
      closeIssue(close, open);
    }
  });

  $(".btn-close-issue").on("click", function(){

    var close = $(this).attr("data-id");
    closeIssue(close,0)
  });
});
