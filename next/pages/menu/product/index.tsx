import { NextPage } from "next";
import Link from "next/link";
import { useRouter } from "next/router";
import { useState } from "react";
import FloatingActionButton from "../../../components/Ui/Buttons/FAB";
import Card from "../../../components/Ui/Card/Card";
import CardLink from "../../../components/Ui/Card/CardLink";
import Input from "../../../components/Ui/Fields/Input";
import Layout from "../../../components/Ui/Layout";

interface IMenuInterface {
  label: string;
  description: string;
  image: JSX.Element;
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

  const router = useRouter();

  return (
    <Layout title="Product" back={true}>
      <div>
        <div>
          <div className="py-3 space-y-8 mb-24">
            {product.map((m, index) => (
              <Link href={`/menu/product/${m.id}`} key={index}>
                <CardLink
                  onClick={(e: React.MouseEvent<HTMLAnchorElement>) => {
                    if (show.delete) e.preventDefault();
                  }}
                >
                  <Card
                    label={m.label}
                    description={m.description}
                    image={m.image}
                    class={{ confirmable: { confirm: "py-7", cancel: "py-7" } }}
                    confirmable={() => alert("CONFIRMED")}
                    action={
                      show.delete ? (
                        <div
                          className="bg-gray-200 flex justify-center items-center rounded-r-lg h-[93px]"
                          id="action-delete"
                        >
                          <img
                            src={"./../assets/icons/Red Delete.svg"}
                            width="50"
                            height="50"
                          />
                        </div>
                      ) : (
                        <></>
                      )
                    }
                    id={m.id}
                  />
                </CardLink>
              </Link>
            ))}
          </div>
          <FloatingActionButton
            title="Add Product"
            action="/menu/product/add"
            dismissable
            options={[
              {
                label: "Search",
                icon: (
                  <img
                    src={"./../assets/icons/Search.svg"}
                    width="30"
                    height="30"
                  />
                ),
                onClick: () => setShow({ search: !show.search }),
              },
              {
                label: "Category",
                icon: (
                  <img
                    src={"./../assets/icons/Diversity.svg"}
                    width="30"
                    height="30"
                  />
                ),
                onClick: () => router.push("/menu/category"),
              },
              {
                label: "Stock",
                icon: (
                  <img
                    src={"./../assets/icons/Add to Collection.svg"}
                    width="30"
                    height="30"
                  />
                ),
                onClick: () => {},
              },
              {
                label: "Delete",
                icon: (
                  <img
                    src={"./../assets/icons/Delete.svg"}
                    width="30"
                    height="30"
                  />
                ),
                onClick: () => setShow({ delete: !show.delete }),
              },
            ]}
          />
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
