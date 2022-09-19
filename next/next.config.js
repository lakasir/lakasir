/** @type {import('next').NextConfig} */
const path = require('path');
const { parsed: localEnv } = require('dotenv').config({
  path: path.resolve(__dirname, '../.env'),
  allowEmptyValues: false,
});

const nextConfig = {
  env: {
    ...localEnv,
  },
  reactStrictMode: true,
  publicRuntimeConfig: {
    ...localEnv,
  },
}

module.exports = nextConfig
