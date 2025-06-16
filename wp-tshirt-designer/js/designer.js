jQuery(document).ready(function($){
    var canvas = new fabric.Canvas('wptd-canvas', {
        backgroundColor: '#fff'
    });

    $('#wptd-image-upload').on('change', function(e){
        var file = e.target.files[0];
        if(!file || file.size > 10 * 1024 * 1024) { // 10 MB limit
            alert('Maksymalny rozmiar pliku to 10 MB');
            return;
        }
        var reader = new FileReader();
        reader.onload = function(f){
            fabric.Image.fromURL(f.target.result, function(img){
                img.set({ left: 0, top: 0, scaleX: 0.5, scaleY: 0.5 });
                canvas.add(img);
            });
        };
        reader.readAsDataURL(file);
    });

    $('#wptd-add-text').on('click', function(){
        var text = $('#wptd-text').val();
        var font = $('#wptd-font').val();
        if(text){
            var txt = new fabric.IText(text, { left: 50, top: 50, fontFamily: font });
            canvas.add(txt);
        }
    });

    function updatePrice(){
        var bounds = canvas.getObjects().reduce(function(area, obj){
            var width = obj.getScaledWidth();
            var height = obj.getScaledHeight();
            return area + (width * height) / (1181/30); // approximate cm^2
        }, 0);
        var price = bounds * 0.1 / 100; // 0.10 zł per cm²
        $('#wptd-price').text('Cena: ' + price.toFixed(2) + ' zł');
    }
    canvas.on('object:modified', updatePrice);
    canvas.on('object:added', updatePrice);

    $('#wptd-add-to-cart').on('click', function(){
        canvas.discardActiveObject();
        canvas.renderAll();
        var image = canvas.toDataURL({format: 'png', multiplier: 1});
        $.post(wptd_ajax.ajax_url, {
            action: 'wptd_save_design',
            nonce: wptd_ajax.nonce,
            image: image
        }, function(response){
            if(response.success){
                alert('Zapisano projekt: ' + response.data.url);
            } else {
                alert('Błąd zapisu projektu');
            }
        });
    });
});
