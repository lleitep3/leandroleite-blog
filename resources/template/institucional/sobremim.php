<div class="hero-unit row">
    <div class="span2">
        <img src="https://lh3.googleusercontent.com/-hb7RdDL5Wtg/AAAAAAAAAAI/AAAAAAAAADE/U5Maa4hq6tA/s250-c-k/photo.jpg" class="img-polaroid">
    </div>
    <div class="span9">
        <h1>Leandro Leite</h1>
        <p>Desenvolvedor Web, Apaixonado pela familia, por Desenvolvimento, OpenSource, Filosofia e Conhecimentos Gerais... Ah, e por <b>PHP</b> é claro!</p>
    </div>
</div>
<div class="row-fluid boxInformation">
</div><!--/span-->

<script src="/script/linkedInNavigator.js"></script>
<script type="text/javascript">
    $(function(){
        // making LinkedIn Integration to populate personal information
        $.get('/linkedin',function(data){
            var $boxInfo = $('.boxInformation');
            var linkedIn = new linkedInNavigator(data);
            
            // making current position html
            $boxInfo.append(
            linkedIn.makeHtmlBasicInformation(linkedIn.getBasicInformation())
        );
            
            // making current position html
            $boxInfo.append(
            linkedIn.makeHtmlCurrentPosition(linkedIn.getCurrentPositions())
        );
            // making past position html
            $boxInfo.append(
            linkedIn.makeHtmlPastPosition(linkedIn.getPastPositions())
        );
            // making skills html
            $boxInfo.append(
            linkedIn.makeHtmlSkills(linkedIn.getSkills())
        );
        },'json');
    });
</script>