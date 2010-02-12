$("document").ready(function() {
  $("#gAddCommentButton").click(function(event) {
    event.preventDefault();
    if (!$("#gAddCommentForm").length) {
      $.get($(this).attr("href"),
	    {},
	    function(data) {
	      $("#gCommentDetail").append(data);
	      ajaxify_comment_form();
	    });
    }
  });
});

function ajaxify_comment_form() {
  $("#gComments form").ajaxForm({
    dataType: "json",
    success: function(data) {
      if (data.form) {
        $("#gComments form").replaceWith(data.form);
        ajaxify_comment_form();
      }
      if (data.result == "success") {
        $.get(data.resource, function(data, textStatus) {
          $("#gComments .gBlockContent ul:first").append("<li>"+data+"</li>");
          $("#gComments .gBlockContent ul:first li:last").effect("highlight", {color: "#cfc"}, 8000);
          $("#gAddCommentForm").hide(2000).remove();
	  $("#gNoCommentsYet").hide(2000);
        });
      }
    }
  });
}
