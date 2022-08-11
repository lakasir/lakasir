import React from "react";
import "../styles/globals.css";

function MyApp({ Component, pageProps }: any) {
  console.log(Component);
  return <Component {...pageProps} />;
}

export default MyApp;
