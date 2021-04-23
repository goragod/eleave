function initDownload(id) {
  var doDelete = function() {
    if (confirm(trans('You want to XXX ?').replace(/XXX/, trans('delete')))) {
      send("index.php/download/model/action/delete", 'id=' + this.id, doFormSubmit, this);
    }
  };
  forEach($G(id).elems('a'), function() {
    if (/^download_delete_([a-z0-9]+)$/.test(this.id)) {
      callClick(this, doDelete);
    }
  });
}