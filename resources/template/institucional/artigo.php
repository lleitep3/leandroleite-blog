<style type="text/css">
    #authorArea hr{
        margin: 5px 0px;
    }
    #authorArea h3{
        margin-top: 0px;
        padding-top: 0px;
        text-decoration: underline;
    }
    #authors .img{
        height: 6em; 
    }
    #authors .name{
        font-size: 0.8em; 
    }
    #authors .ownerBlock{
        display: inline-block;
        text-align: center;
    }
    #authors .well{
        padding: 10px;
    }
</style>
<script type="text/javascript">
    function renderAuthor(item) {
        return '<div class="ownerBlock well">' +
                '<div class="pic">' +
                '<img src="' + item.picture.url + '"/>' +
                '</div>' +
                '<div class="name">' + item.displayName + '</div>' +
                '</div>';
    }
    $(function() {
        var regex = /^\/|\/$/g;
        var uri = window.location.pathname.replace(regex, '').split('/');
        $.get('/api/v1/integration/google/article/' + uri[uri.length - 1], function(data) {
            $('#articleContent').html(data.content);
            $('#authors').html('');
            for (var k in data.owners)
                $('#authors').append(renderAuthor(data.owners[k]));
        }, 'json');
    });
</script>

<div class="span11" id="article">
    <div class="row-fluid">
        <div id="articleContent" class="span11">
            <img src="/image/loading1.gif">
        </div>
        <div id="authorArea" class="span11">
            <hr/>
            <h3>Autores</h3>
            <div id="authors"><img src="/image/loading1.gif"></div>
        </div>
    </div><!--/span-->
</div><!--/span-->
