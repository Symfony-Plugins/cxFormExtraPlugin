CxOpenListOptionFactory = {
  make: function(src)
  {
    text = src.value;
    if(src.attributes['optval'])
    {
      value = src.attributes['optval'].value;
      opt = new Option(text, value);
      return opt;
    }
    return;
  },
  makeTag: function(src) {
    tag = src.value.toLowerCase();
    if(tag){
      opt = new Option(src.value.toLowerCase());
      return opt;
    }
    return;
  }
}

CxOpenSelect = function(className, srcId, destId, jsonUrl, config, optionFactory)
{
  this.src = document.getElementById(srcId);
  this.dest = document.getElementById(destId);
  this.optionFactory = optionFactory;
  
  instance = this;
  
  this.getForm = function(el)
  {
    if ("form" != el.tagName.toLowerCase())
    {
      return this.getForm(el.parentNode);
    }
    return el;
  }

  this.add = function()
  {
    var opt = this.optionFactory(this.src);
    
    // exit if option is null
    if(!opt) return;

    this.src.value = '';
    delete this.src.attributes['optval'];

    // exit if option already exists in list
    for (var i = 0; i < this.dest.options.length; i++)
    {
      if (this.dest.options[i].value == opt.value) return;
    }
    
    this.dest.options[this.dest.options.length] = opt;
  }
  
  this.remove = function()
  {

    for (var i = 0; i < this.dest.options.length; i++)
    {
      if (this.dest.options[i].selected)
      {
        this.dest.options[i] = null;
        --i;
      }
    }
  }

  this.submit = function()
  {
    for (var j = 0; j < this.dest.options.length; j++)
    {
      this.dest.options[j].selected = true;
    }
    return true;
  }

  
  jQuery('#'+srcId)
  .autocomplete(jsonUrl, jQuery.extend({}, {
    dataType: 'json',
    parse:    function(data) {
      var parsed = [];
      for (key in data) {
        parsed[parsed.length] = { data: [ data[key], key ], value: data[key], result: data[key] };
      }
      return parsed;
    }
  }, config))
  .bind("keypress",
  {instance: instance},
  function(e) {
    // check return key
    if (e.keyCode == 13)
    {
      e.data.instance.add();
      return false;
    }
  })
 .bind("change",
    function(e) {
      jQuery(this).removeAttr('optval');
    }
  )
  .result(
    function(event, data) {
      jQuery(this).attr('optval', data[1]); 
    }
  );  

  jQuery('#' + srcId).parents('.' + className).find('.' + className + "_add")
  .bind("click", 
  {handler: this},
  function(e) {
    e.data.handler.add();
  });
  
  jQuery('#' + srcId).parents('.' + className).find('.' + className + "_remove")
  .bind("click", 
  {handler: this},
  function(e) {
    e.data.handler.remove();
  });

  jQuery(this.dest.form)
  .bind("submit", 
      {handler: this},
      function(e) {
        e.data.handler.submit();
  });
}

