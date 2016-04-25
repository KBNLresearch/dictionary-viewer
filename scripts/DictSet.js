// Dictionary Set "class"

var DictSet = function() {
    this.dicts = [];
    this.count = 0;
};

DictSet.prototype.add = function(dictObj) {    
    dictObj.id = this.count;
    dictObj.color = settings.colors[this.count % 5];
    this.count++;
    this.dicts.push(dictObj);
    
    $('table#list').append('<tr class="listElement" id="dict_' + dictObj.id + '"></tr>');
    
    $('tr#dict_' + dictObj.id)
        .append('<td><div class="legend"></div></td>')
        .append('<td><p class="string">' + dictObj.string + '</td>')
        .append('<td class="count">' + dictObj.data.count + '</td>')
        .append('<td class="min_words">' + dictObj.data.min_words + '</td>')
        .append('<td><a class="download">Download</a></td>')
        .append('<td><a class="remove">Delete</a></td>');
    
    $('tr#dict_' + dictObj.id + ' div.legend').css('background-color', dictObj.color);
};

DictSet.prototype.remove = function(dictId) {
    var dictIndex = this.getIndexById(dictId);
    this.dicts.splice(dictIndex, 1);
    $('tr#dict_' + dictId).remove();
};

DictSet.prototype.getMaxFreq = function(freqType) {
    var maxFreq = 0;
    this.dicts.forEach(function(dictObj) {
        dictObj.data.frequencies.forEach(function(dataObj){
            if(dataObj[freqType] > maxFreq) {
                maxFreq = dataObj[freqType];
            }
        });
    });
    return maxFreq;
};

DictSet.prototype.getIndexById = function(dictId) {    
    for (var i = 0; i < this.dicts.length; i++) {
        if (this.dicts[i].id === parseInt(dictId)) { 
            return i;
        }
    }
};

DictSet.prototype.getDictById = function(dictId) {
    for (var i = 0; i < this.dicts.length; i++) {
        if (this.dicts[i].id === parseInt(dictId)) { 
            return this.dicts[i];
        }
    }
};

