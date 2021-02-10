import './styles/app.scss';
import 'materialize-css';
import 'materialize-css/dist/css/materialize.min.css';
import 'material-icons';


var elems = document.querySelectorAll('select');
var instances = M.FormSelect.init(elems);

$('select').change(function() {
    var donnees = {
        'searchSelector': $(this).children('option:selected').val()
    };
    $.ajax({
        url: '/search/keyworddata',
        type: 'POST',
        dataType: 'json',
        data: donnees,
        async: true,
        success: function(data, status) {
            var tab = [];
            for (var i = 0; i < data.length; i++) {
                tab[i] = data[i].firstname + ' ' + data[i].lastname;
            }
            autocomplete(document.getElementById("keywords"), tab);
        },
        error: function(xhr, textStatus, errorThrown) {
            console.log('Ajax request failed. ' + errorThrown);
        }
    });
});
$('#keywords').keyup(function() {
    var keywords = $('#keywords').val();
    var tagselect = $('#tag-select').val();
    $('#asb').attr("href", "/search/data?keywords=" + keywords + "&searchfilter=" + tagselect);
});