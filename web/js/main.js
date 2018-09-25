const SERVICE_PATH = 'geolocation';

class GeolocationClient {
    static getGeoInfoByIp(ip) {
        $.ajax({
            url: SERVICE_PATH + '/' + ip,
            dataType: 'json',
            success: function(result) {
                GeolocationClient.renderResult(result);
            }
        });
    }
    
    static renderResult(jsonResult) {
        var result = '<table>';
        
        for (var key in jsonResult) {
            result += '<tr><td><b>' + key + ': </b></td><td>' + jsonResult[key] + '</td></tr>'
        }
        
        result += '</table>';
        
        $('#result').html(result);
    }
}

$(function() {
    $('#getgeo').click(function() {
        var ip = $('#ip').val();
        GeolocationClient.getGeoInfoByIp(ip);
    });
});
