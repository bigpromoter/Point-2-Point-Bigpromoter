(function() {
   tinymce.create('tinymce.plugins.recentposts', {
      init : function(ed, url) {
         ed.addButton('p2p_bp_map', {
            title : '[P2P] Add Map',
            image : url+'/../images/icon_map.png',
            onclick : function() {
               window.send_to_editor("[p2p_bp_map]");
            }
         });
         ed.addButton('p2p_bp_reservation', {
            title : '[P2P] Add Reservation',
            image : url+'/../images/icon_reservation.png',
            onclick : function() {
               window.send_to_editor("[p2p_bp_reservation]");
            }
         });
      },
      createControl : function(n, cm) {
         return null;
      },
      getInfo : function() {
         return {
            longname    : "Point 2 Point - Big Promoter",
            author      : 'BigPromoter'
         };
      }
   });
   tinymce.PluginManager.add('p2p_bp', tinymce.plugins.recentposts);
})();