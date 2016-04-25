// DictChart "class"

var DictChart = function () {

    this.frequencyType = 'rf';
    
    // Set canvas and chart dimensions
    this.canvas = {width: 870, heigth: 420};
    this.margin = {top: 10, right: 20, bottom: 25, left: 50};
    this.width = this.canvas.width - this.margin.left - this.margin.right;
    this.height = this.canvas.heigth - this.margin.top - this.margin.bottom;

    // Adds the svg canvas
    svg = d3.select("div#chart")
        .append("svg")
        .attr("width", this.width + this.margin.left + this.margin.right)
        .attr("height", this.height + this.margin.top + this.margin.bottom)
        .append("g")
        .attr("transform", "translate(" + this.margin.left + "," + this.margin.top + ")");

};

DictChart.prototype.draw = function () {
    
    var self = this;
    
    d3.selectAll(".tick").remove();
    d3.selectAll(".node").remove();
    d3.selectAll(".line").remove();
    d3.selectAll(".axis").remove();
    
    // Ranges
    var x = d3.time.scale().range([0, this.width]);
    var y = d3.scale.linear().range([this.height, 0]);
    
    // Domains
    x.domain([1800, 2000]);
    y.domain([0, 1.05 * app.dictSet.getMaxFreq(self.frequencyType)]);

    // Axes
    var xAxis = d3.svg.axis().scale(x).orient("bottom").ticks(5).tickSize(7, 0).tickPadding(5).tickFormat(d3.format("d"));
    var yAxis;
    if (self.frequencyType === 'af') {
        yAxis = d3.svg.axis().scale(y).orient("left").ticks(5).tickSize(7, 0).tickPadding(5).tickFormat(d3.format("."));
    } else {
        yAxis = d3.svg.axis().scale(y).orient("left").ticks(5).tickSize(7, 0).tickPadding(5);
    }
    
    // Add the X Axis        
    svg.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + self.height + ")")
        .call(xAxis);

    // Add the Y Axis
    svg.append("g")
        .attr("class", "y axis")
        .call(yAxis);
    
    // Line
    var valueline = d3.svg.line()
        .x(function (d) {
            return x(d.yr);
        })
        .y(function (d) {
            if (self.frequencyType === 'af') {
                return y(d.af);
            }
            return y(d.rf);
        });
        
    // var count = 0;
    
    app.dictSet.dicts.forEach(function(dictObj) {
        
        // Get data
        var data = dictObj.getFrequencies();

        // Add the valueline path
        svg.append("path")
                .attr("class", "line dict_" + dictObj.id)
                .attr("d", valueline(data));

        // Add value points and links
        var valuepoint = this.svg.selectAll("g.dict_" + dictObj.id).data(data, function (d) {
            return d.yr;
        });

        var valuepointGroup = valuepoint.enter().append("g").attr("class", "node")
            .attr('transform', function (d) {
                if (self.frequencyType === 'af') {
                    return "translate(" + x(d.yr) + "," + y(d.af) + ")";
                }
                return "translate(" + x(d.yr) + "," + y(d.rf) + ")";
            });

        var tip = d3.tip()
            .attr('class', 'd3-tip')
            .offset([-10, 0])
            .html(function (d) {
                if (self.frequencyType === 'af') {
                    return d.yr + ' # ' + d.af;
                }
                    return d.yr + ' # ' + d.rf;
            });

        this.svg.call(tip);
        
            /*
            .attr("xlink:href", function (d) {
                return 'http://www.dbnl.org/zoeken/zoekeninteksten/corpusZoek.php?zoek=' + queryComponent + genreComponent + '&pri_jaar=' + d.yr;
            })
            */
        
        valuepointGroup.append("svg:a")
            .attr('id', function (d) { return d.yr; })
            .on('mouseover', tip.show)
            .on('mouseout', tip.hide)
            .on('click', function () { 
                dictObj.linkToResultsPerYear($(this).attr('id')); 
            })
            .append("circle")
            .attr("r", 3)
            .attr("class", "dot dict_" + dictObj.id)
            .on('mouseover', function () {
                d3.select(this).transition()
                        .ease('exp-out')
                        .attr('r', 6);
            })
            .on('mouseout', function () {
                d3.select(this).transition()
                        .ease('exp-out')
                        .attr('r', 3);
            });
        
        // Set CSS properties
        $('path.dict_' + dictObj.id).css('stroke', dictObj.color);
        $('circle.dict_' + dictObj.id).css('fill', dictObj.color);
        
    });

};

DictChart.prototype.toggleFrequencyType = function(freqType) {
    this.frequencyType = freqType;
    this.draw();
};

