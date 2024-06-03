var Constants = {
    get_api_base_url: function () {
      if(location.hostname == 'localhost'){
        return "http://localhost:80/study-zone/rest/";
      } else {
        return "https://dolphin-app-pfh9z.ondigitalocean.app/rest/";
      }
    }
  };