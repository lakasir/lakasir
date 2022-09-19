import { NextPage } from "next";
import Link from "next/link";
import { useRouter } from "next/router";
import { useState } from "react";
import FloatingActionButton from "../../../components/Ui/Buttons/FAB";
import Card from "../../../components/Ui/Card/Card";
import CardLink from "../../../components/Ui/Card/CardLink";
import Input from "../../../components/Ui/Fields/Input";
import Layout from "../../../components/Ui/Layout";
import Modal from "../../../components/Ui/Modals";

interface IMenuInterface {
  label: string;
  description: string;
  sub_description: string;
  image: JSX.Element;
  id: number;
}

const product: IMenuInterface[] = [
  {
    id: 1,
    label: "Pizza",
    description: "100 stock",
    sub_description: "Rp. 10.000,00 - Rp. 12.000,00",
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
    label: "Fried Chicken",
    description: "- stock",
    sub_description: "Rp. 25.000,00 - Rp. 28.000,00",
    image: (
      <img
        src={"./../assets/products/KFC.jpg"}
        width="100%"
        height="100%"
        className="rounded-lg"
      />
    ),
  },
  {
    id: 3,
    label: "Tiramisu Cofee",
    description: "- stock",
    sub_description: "Rp. 20.000,00 - Rp. 22.000,00",
    image: (
      <img
        src={"./../assets/products/cofee.jpg"}
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
  stock?: boolean;
}

const Product: NextPage = () => {
  const [show, setShow] = useState<ShowActionInterface>({
    delete: false,
    option: false,
    search: false,
    confirm: false,
    stock: false,
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
                    sub_description={m.sub_description}
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
                onClick: () => router.push("/menu/product/stock"),
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
        <Modal open={show.search != undefined ? show.search : false} onClose={setShow}>
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
        </Modal>
      </div>
    </Layout>
  );
};

export default Product;
