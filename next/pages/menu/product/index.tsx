import { NextPage } from "next";
import Link from "next/link";
import Button from "../../../components/Ui/Buttons/Button";
import Layout from "../../../components/Ui/Layout";

interface IMenuInterface {
  label: string;
  description: string;
  image: string | JSX.Element;
}

const product: IMenuInterface[] = [
  {
    label: "Product A",
    description: "100 stock",
    image: (
      <img
        src={"./../assets/products/product-image.jpg"}
        width="100%"
        height="100%"
        className="rounded-lg"
      />
    ),
  },
  {
    label: "Product A",
    description: "100 stock",
    image: (
      <img
        src={"./../assets/products/product-image.jpg"}
        width="100%"
        height="100%"
        className="rounded-lg"
      />
    ),
  },
  {
    label: "Product A",
    description: "100 stock",
    image: (
      <img
        src={"./../assets/products/product-image.jpg"}
        width="100%"
        height="100%"
        className="rounded-lg"
      />
    ),
  },
  {
    label: "Product A",
    description: "100 stock",
    image: (
      <img
        src={"./../assets/products/product-image.jpg"}
        width="100%"
        height="100%"
        className="rounded-lg"
      />
    ),
  },
  {
    label: "Product A",
    description: "100 stock",
    image: (
      <img
        src={"./../assets/products/product-image.jpg"}
        width="100%"
        height="100%"
        className="rounded-lg"
      />
    ),
  },
  {
    label: "Product A",
    description: "100 stock",
    image: (
      <img
        src={"./../assets/products/product-image.jpg"}
        width="100%"
        height="100%"
        className="rounded-lg"
      />
    ),
  },
  {
    label: "Product A",
    description: "100 stock",
    image: (
      <img
        src={"./../assets/products/product-image.jpg"}
        width="100%"
        height="100%"
        className="rounded-lg"
      />
    ),
  },
  {
    label: "Product A",
    description: "100 stock",
    image: (
      <img
        src={"./../assets/products/product-image.jpg"}
        width="100%"
        height="100%"
        className="rounded-lg"
      />
    ),
  },
  {
    label: "Product A",
    description: "100 stock",
    image: (
      <img
        src={"./../assets/products/product-image.jpg"}
        width="100%"
        height="100%"
        className="rounded-lg"
      />
    ),
  },
  {
    label: "Product A",
    description: "100 stock",
    image: (
      <img
        src={"./../assets/products/product-image.jpg"}
        width="100%"
        height="100%"
        className="rounded-lg"
      />
    ),
  },
  {
    label: "Product A",
    description: "100 stock",
    image: (
      <img
        src={"./../assets/products/product-image.jpg"}
        width="100%"
        height="100%"
        className="rounded-lg"
      />
    ),
  },
];

const Product: NextPage = () => {
  return (
    <Layout title="Product" back={true}>
      <div>
        <div className="py-3 space-y-8 mb-24">
          <a className="block relative">
            <div className="w-full h-24 rounded-lg overflow-hidden cursor-pointer">
              <div className="bg-lakasir-primary rounded-lg w-[91px] h-[93px] absolute flex items-center justify-center overflow-hidden">
                <img
                  src={"./../assets/products/product-image.jpg"}
                  width="100%"
                  height="100%"
                  className="rounded-lg"
                />
              </div>
              <div className="flex">
                <div className="bg-lakasir-primary rounded-lg w-[91px] h-[93px]"></div>
                <div className="w-3/4 flex">
                  <div className="items-center ml-7 mt-5">
                    <p className="text-xl">Delete</p>
                    <p className="font-light text-sm">description</p>
                  </div>
                </div>
                <div className="mr-auto">
                  <div className="absolute right-0 w-1/4 h-[93px] bg-gray-200 flex justify-center items-center rounded-r-lg">
                    <img
                      src={"./../assets/icons/Red Delete.svg"}
                      width="50"
                      height="50"
                    />
                  </div>
                </div>
              </div>
            </div>
          </a>
          <a className="block relative">
            <div className="w-full h-24 rounded-lg overflow-hidden cursor-pointer flex">
              <div className="w-1/2 flex items-center justify-center my-auto text-2xl font-semibold bg-red-600 h-full text-white">
                Confirm!
              </div>
              <div className="w-1/2 flex items-center justify-center my-auto text-2xl font-semibold h-full bg-gray-200">
                Cancel!
              </div>
            </div>
          </a>
          {product.map((m, index) => (
            <a className="block relative" key={index}>
              <div className="w-full h-24 rounded-lg overflow-hidden cursor-pointer">
                <div className="bg-lakasir-primary rounded-lg w-[91px] h-[93px] absolute flex items-center justify-center overflow-hidden">
                  {m.image}
                </div>
                <div className="flex">
                  <div className="bg-lakasir-primary rounded-lg w-[91px] h-[93px]"></div>
                  <div className="w-3/4 flex">
                    <div className="items-center ml-7 mt-5">
                      <p className="text-xl">{m.label}</p>
                      <p className="font-light text-sm">{m.description}</p>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          ))}
        </div>
        <div className="flex justify-between items-end fixed bottom-0 w-11/12 right-4">
          <div>
            <Button
              className="w-14 py-4 rounded-full flex justify-center items-center drop-shadow-2xl h-14 bg-red-500 mb-5"
              onClick={() => console.log("ok")}
            >
              <img
                src={"./../assets/icons/Add to Collection.svg"}
                width="30"
                height="30"
              />
            </Button>
            <Button
              className="w-14 py-4 rounded-full flex justify-center items-center drop-shadow-2xl h-14 bg-red-500 mb-5"
              onClick={() => console.log("ok")}
            >
              <img
                src={"./../assets/icons/Delete.svg"}
                width="30"
                height="30"
              />
            </Button>
            <Button
              className="w-14 py-4 rounded-xl flex justify-center items-center drop-shadow-2xl h-14"
              onClick={() => console.log("ok")}
            >
              <img
                src={"./../assets/icons/Single Choice.svg"}
                width="30"
                height="30"
              />
            </Button>
          </div>
          <Link href={"/menu/product/add"}>
          <Button className="w-4/5 py-4 rounded-xl drop-shadow-2xl text-lg font-semibold h-14">
          Add Product
          </Button>
          </Link>
        </div>
      </div>
    </Layout>
  );
};

export default Product;
