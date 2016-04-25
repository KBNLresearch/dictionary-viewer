// Dictionary "class" 

var Dict = function(dictProperties) {
    this.id;
    this.color;
    this.data;
    this.string;
    this.min_words;
};

Dict.prototype.setData = function(data) {
    var self = this;
    self.data = JSON.parse(data);
    if (self.data.status !== 'success') {
        if (self.data.error) {
            throw self.data.error;
        } else {
            throw 'Error getting results';
        }
    } else {
        self.string = self.data.string;
        self.min_words = self.data.min_words;
    }
};

Dict.prototype.download = function() {
    str = JSON.stringify(this.data.frequencies);
    str = str.replace(/"/g, '&quot;');
    // console.log(str);
    var url = '/dictionary/api/download.php';
    var form = $('<form action="' + url + '" method="post" target="_blank" enctype="multipart/form-data">' +
      '<input type="text" name="json" value="' + str + '" />' +
      '</form>');
    $('body').append(form);
    form.submit();
    form.remove();
};

Dict.prototype.linkToResultsPerYear = function(year) {
    var queryComponent = this.data.query;
    queryComponent = queryComponent.replace(/"/g, '&quot;');
    var url = 'detail.php?q=' + this.data.query + '&y=' + year + '&mw=' + this.data.min_words;
    var form = $('<form action="' + url + '" method="post" target="_blank" enctype="multipart/form-data"></form>');
    $('body').append(form);
    form.submit();
    form.remove();
};

Dict.prototype.getData = function() {
    return this.data;
};

Dict.prototype.getFrequencies = function() {
    return this.data.frequencies;
};

