const presets = [
  ["@babel/env", {
    targets: {
      edge: "17",
      firefox: "58",
      chrome: "65",
      safari: "11",
      ie: "10"
    },
    useBuiltIns: "usage",
    corejs: 3
  }]
];

const plugins = [
  "@babel/plugin-transform-arrow-functions",
  "@babel/plugin-proposal-class-properties"
  // "@babel/plugin-transform-template-literals"
];

module.exports = { presets, plugins };
