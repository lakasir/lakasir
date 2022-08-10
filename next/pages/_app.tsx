import React from 'react'

function MyApp({ Component, pageProps }: any) {
  console.log(Component);
  return <Component {...pageProps} />

}

export default MyApp
