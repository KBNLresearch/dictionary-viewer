// Namespaces
var app = {};
app.dictSet = {};
app.dictChart = {};

var block = false; 

// Settings
var settings = {};
settings.colors = ['#003366', '#6699FF', '#347C17', '#FF6600', '#CC0000'];

// Event handlers
$(document).ready(function() {
    
    $('#form').submit(function(event) {
        event.preventDefault();
        if (!$('#dict').val() == '' && block == false) {
            block = true;
            $('#message p').remove();
            $('#message').append('<p class="loading">Loading results, please wait.</p>');
 
            var dict_terms = $('#dict').val();
            var min_words = $('#words').val();
            $('#dict').val('');
            $('#words').val(1);
            $("#words").selecter("update");

            var urlComponent = encodeURIComponent(dict_terms);
            var baseUrl = "/dictionary/api/data.php";

            var xhr = $.ajax({
                url: baseUrl + '?q=' + urlComponent + '&mw=' + min_words,
                async: true,
                timeout: 300000
            })
            .done(function (data) {
                try{
                    var dict = new Dict();
                    dict.setData(data);
                    app.dictSet.add(dict);
                    app.dictChart.draw();
                    $('#message p.loading').remove();
                } catch (error) {
                    $('#message p.loading').remove();
                    $('#message').append('<p>'+ error +'</p>');
                } finally {
                }
            })
            .fail(function() {
                $('#message p.loading').remove();
                $('#message').append('<p>Error getting results</p>');
            })
            .always(function() {
                block = false;
            });
        }
    });
    
    $('table#list').on('click', 'a.remove', function(event) {
        event.preventDefault();
        var dictIdStr = $(this).closest('.listElement').attr('id');
        var dictId = dictIdStr.substring(5);
        app.dictSet.remove(dictId);
        app.dictChart.draw();
    });
    
    $('table#list').on('click', 'a.download', function(event) {
        event.preventDefault();
        var dictIdStr = $(this).closest('.listElement').attr('id');
        var dictId = dictIdStr.substring(5);
        var dictObj = app.dictSet.getDictById(dictId);
        dictObj.download();
    });
    
    $('input.freq').on('click', function(event) {
        event.preventDefault();
        if ($(this).hasClass('selected') === false) {
            $('input.freq').removeClass('selected');
            $(this).addClass('selected');
            var freqType = 'rf';
            if($(this).attr('id') === 'absolute') {
                freqType = 'af';
            } 
            app.dictChart.toggleFrequencyType(freqType);
        }        
    });
    
    // Init
    $('#words').selecter();
    app.dictSet = new DictSet();
    app.dictChart = new DictChart();
    app.dictChart.draw();
  
});

