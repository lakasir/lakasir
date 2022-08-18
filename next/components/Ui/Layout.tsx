import Head from "next/head";
interface ILayoutInterface {
  children: JSX.Element;
  title?: string;
}

const Layout = (props: ILayoutInterface) => {
  return (
    <>
      <Head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link
          rel="preconnect"
          href="https://fonts.gstatic.com"
          crossOrigin="anonymous"
        />
        <link
          href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700;1,900&display=swap"
          rel="stylesheet"
        />
      </Head>
      {props.title ? (
        <div className="flex justify-center items-center max-h-14 h-[52px] font-semibold text-xl">
          {props.title}
        </div>
      ) : (
        ""
      )}
      <div className="mx-auto w-11/12">{props.children}</div>
    </>
  );
};

export default Layout;
