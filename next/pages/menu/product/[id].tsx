import { NextPage } from "next";
import Image from "next/image";
import { useRouter } from "next/router";

const Detail: NextPage = () => {
  const router = useRouter();
  const { id } = router.query;
  return (
    <div className="h-screen absolute">
      <div className="w-screen h-72 overflow-hidden">
        <Image
          src={"/assets/products/product-image.jpg"}
          layout="responsive"
          width={"100%"}
          height={"100%"}
        />
      </div>
      <div className="w-full rounded-t-2xl relative -top-5 bg-white px-3 py-3">
        <div className="w-24 h-3 bg-gray-200 rounded-lg mx-auto"></div>
      </div>
    </div>
  );
};

export default Detail;
