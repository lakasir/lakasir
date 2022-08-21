import { NextPage } from "next";
import Link from "next/link";
import { useState } from "react";
import Button from "../../../components/Ui/Buttons/Button";
import Input from "../../../components/Ui/Fields/Input";
import Layout from "../../../components/Ui/Layout";

interface IMenuInterface {
  label: string;
  description: string;
  image: string | JSX.Element;
  id: number;
}

const product: IMenuInterface[] = [
  {
    id: 1,
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
    id: 2,
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
    id: 3,
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

interface ShowActionInterface {
  delete?: boolean;
  option?: boolean;
  search?: boolean;
  confirm?: boolean;
}

const Product: NextPage = () => {
  const [show, setShow] = useState<ShowActionInterface>({
    delete: false,
    option: false,
    search: false,
    confirm: false,
  });

  return (
    <Layout title="Product" back={true}>
      <div>
        <div>
          <div className="py-3 space-y-8 mb-24">
            {product.map((m, index) => (
              <Link href={`/menu/product/${m.id}`} key={index}>
                <a
                  className="block relative"
                  id="action-detail"
                  onClick={(e) => {
                    if (show.delete) e.preventDefault();
                  }}
                >
                  <div
                    className="w-full h-24 rounded-lg overflow-hidden cursor-pointer hidden"
                    id={`delete-confirm-${m.id}`}
                  >
                    <div
                      className="w-1/2 flex items-center justify-center my-auto text-2xl font-semibold bg-red-600 h-full text-white"
                      onClick={() => {
                        document
                          .querySelector(`#delete-confirm-${m.id}`)
                          ?.classList.remove("flex");
                        document
                          .querySelector(`#delete-confirm-${m.id}`)
                          ?.classList.add("hidden");
                      }}
                    >
                      Confirm!
                    </div>
                    <div
                      className="w-1/2 flex items-center justify-center my-auto text-2xl font-semibold h-full bg-gray-200"
                      id={`cancel-delete${m.id}`}
                      onClick={() => {
                        document
                          .querySelector(`#delete-confirm-${m.id}`)
                          ?.classList.remove("flex");
                        document
                          .querySelector(`#delete-confirm-${m.id}`)
                          ?.classList.add("hidden");
                        document
                          .querySelector(`#product-list-${m.id}`)
                          ?.classList.remove("hidden");
                      }}
                    >
                      Cancel!
                    </div>
                  </div>
                  <div
                    className="w-full h-24 rounded-lg overflow-hidden cursor-pointer"
                    id={`product-list-${m.id}`}
                  >
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
                          <p className="text-xl">{m.label}</p>
                          <p className="font-light text-sm">{m.description}</p>
                        </div>
                      </div>
                      {show.delete ? (
                        <div
                          className="mr-auto"
                          id="action-delete"
                          onClick={() => {
                            document
                              .querySelector(`#delete-confirm-${m.id}`)
                              ?.classList.remove("hidden");
                            document
                              .querySelector(`#delete-confirm-${m.id}`)
                              ?.classList.add("flex");
                            document
                              .querySelector(`#product-list-${m.id}`)
                              ?.classList.add("hidden");
                          }}
                        >
                          <div className="absolute right-0 w-1/4 h-[93px] bg-gray-200 flex justify-center items-center rounded-r-lg">
                            <img
                              src={"./../assets/icons/Red Delete.svg"}
                              width="50"
                              height="50"
                            />
                          </div>
                        </div>
                      ) : (
                        ""
                      )}
                    </div>
                  </div>
                </a>
              </Link>
            ))}
          </div>
          <div className="flex justify-between items-end fixed bottom-0 w-11/12 right-4">
            <div>
              {show.option ? (
                <div id="action-option">
                  <Button
                    className="w-14 py-4 rounded-full flex justify-center items-center drop-shadow-2xl h-14 bg-red-500 mb-5"
                    onClick={() => setShow({ search: !show.search })}
                  >
                    <img
                      src={"./../assets/icons/Search.svg"}
                      width="30"
                      height="30"
                    />
                  </Button>
                  <Button
                    className="w-14 py-4 rounded-full flex justify-center items-center drop-shadow-2xl h-14 bg-red-500 mb-5"
                    onClick={() => console.log("ok")}
                  >
                    <img
                      src={"./../assets/icons/Diversity.svg"}
                      width="30"
                      height="30"
                    />
                  </Button>
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
                    onClick={() => {
                      document
                        .querySelectorAll("#action-detail")
                        .forEach((e: Element) => {
                          e.setAttribute("href", "");
                        });
                      setShow({ delete: !show.delete });
                    }}
                  >
                    <img
                      src={"./../assets/icons/Delete.svg"}
                      width="30"
                      height="30"
                    />
                  </Button>
                </div>
              ) : (
                ""
              )}
              <Button
                className="w-14 py-4 rounded-xl flex justify-center items-center drop-shadow-2xl h-14"
                onClick={() => setShow({ option: !show.option })}
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
        {show.search ? (
          <div
            className="z-50 top-0 left-0 fixed h-screen w-screen bg-black opacity-90"
            id="filter-search"
          >
            <div
              className="flex justify-center items-center h-full"
              onClick={(e) => {
                if (!(e.target as Element).matches("input")) {
                  setShow({ search: false });
                }
              }}
            >
              <div className="w-11/12 mx-auto">
                <Input
                  name={"Search"}
                  type={"text"}
                  className="rounded-r-none border-r-0"
                  append={
                    <div
                      className="bg-gray-100 rounded-r-lg w-1/5 flex justify-center items-center cursor-pointer border-2 border-gray-300"
                      onClick={() => alert("ok")}
                    >
                      <img src="./../assets/icons/Search-Red.svg" />
                    </div>
                  }
                />
              </div>
            </div>
          </div>
        ) : (
          ""
        )}
      </div>
    </Layout>
  );
};

export default Product;
