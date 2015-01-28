define(['i18n!nls/labels'], function(labels) {
  var SPOT_OUT = { code: 'SPOT_OUT', label: labels.annonce.typeStationnement.enum.SPOT_OUT};
  var SPOT_IN = { code: 'SPOT_IN', label: labels.annonce.typeStationnement.enum.SPOT_IN};
  var BOX_OUT = { code: 'BOX_OUT', label: labels.annonce.typeStationnement.enum.BOX_OUT};
  var BOX_IN = { code: 'BOX_IN', label: labels.annonce.typeStationnement.enum.BOX_IN};

  var TypeStationnementEnum = {
    values: [SPOT_OUT, SPOT_IN, BOX_OUT, BOX_IN ],

    SPOT_OUT: SPOT_OUT,
    SPOT_IN: SPOT_IN,
    BOX_OUT: BOX_OUT,
    BOX_IN: BOX_IN
  };

  return TypeStationnementEnum;
});