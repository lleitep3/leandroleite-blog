function linkedInNavigator(data){
    linkedInNavigator.prototype.data = data;
    linkedInNavigator.prototype.getSkills = function(){
        var skills = [];
        for(var k in linkedInNavigator.prototype.data.skills.values){
            skills.push(linkedInNavigator.prototype.data.skills.values[k].skill.name);
        }
        return skills;
    };
    linkedInNavigator.prototype.getBasicInformation = function(){
        return {
            email : linkedInNavigator.prototype.data.emailAddress,
            interests : linkedInNavigator.prototype.data.interests
        };
    };
    linkedInNavigator.prototype.getCurrentPositions = function(){
        return linkedInNavigator.prototype.data.threeCurrentPositions.values;
    };
    linkedInNavigator.prototype.getPastPositions = function(){
        return linkedInNavigator.prototype.data.threePastPositions.values;
    };
    linkedInNavigator.prototype.makeHtmlBasicInformation = function(info){
        var html = $('<div/>')
        .addClass('span10 boxBasicInformation')
        .append('<h3>Informações Básicas: </h3>')
        .append('<hr/>');
        html.append('<p><b>Contato :</b>'+info.email+' </p>');
        html.append('<p><b>Interesses :</b>'+info.interests+' </p>');
        return html;
    };
    linkedInNavigator.prototype.makeHtmlSkills = function(skills){
        var html = $('<div/>');
        html.addClass('span9 boxSkill');
        html.append('<h3>Skills</h3>').append('<hr/>');
        
        for(var k in skills){
            html.append('<span class="badge badge-info skill">'+skills[k]+'</span>');
        }
        return html;
    };
    linkedInNavigator.prototype.makeHtmlCurrentPosition = function(currentPositions){
        var html = $('<div/>')
        .addClass('span9 boxCurrentPositions')
        .append('<h3>Atualmente Trabalhando em: </h3>')
        .append('<hr/>');
        for(var k in currentPositions){
            var current = currentPositions[k];
            var company = $('<div/>');
            var period = $('<p class="period"/>');
            company.append('<h4 class="subTitle">'+current.title+'</h4>');
            company.append('<h4 class="subTitle">'+current.company.name+'</h4>');
            period.append('<abbr>'+current.startDate.month+'/'+current.startDate.year+'</abbr>');
            company.append(period);
            company.append('<p>'+current.summary+'</p>');
            html.append(company);
        }
        return html;
    };
    linkedInNavigator.prototype.makeHtmlPastPosition = function(pastPositions){
        var html = $('<div/>')
        .addClass('span9 boxPastPositions')
        .append('<h3>Trabalhando Anteriores: </h3>')
        .append('<hr/>');
        for(var k in pastPositions){
            var current = pastPositions[k];
            var company = $('<div/>');
            var period = $('<p class="period"/>');
            company.append('<h4 class="subTitle">'+current.title+'</h4>');
            company.append('<h4 class="subTitle">'+current.company.name+'</h4>');
            period.append('<abbr>'+current.startDate.month+'/'+current.startDate.year+'</abbr> - ');
            period.append('<abbr>'+current.endDate.month+'/'+current.endDate.year+'</abbr>');
            company.append(period);
            company.append('<p>'+current.summary+'</p>');
            html.append(company);
            if(k < (pastPositions.length - 1))
                html.append('<hr/>');
        }
        return html;
    };
}
