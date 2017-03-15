$('#reportModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var commentId = button.data('comment-id')
  //alert(commentId)
  var commentContent = button.data('comment-content')
  var modal = $(this)
  modal.find('.modal-title').text('Signaler le commentaire numéro ' + commentId)
  modal.find('.modal-body #previewComment').text(commentContent)
  modal.find('.modal-body #reporting_commentConcernedId').val(commentId)
})