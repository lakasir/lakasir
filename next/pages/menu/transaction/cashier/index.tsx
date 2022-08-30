import { ShoppingCartIcon } from "@heroicons/react/outline";
import { NextPage } from "next";
import Image from "next/image";
import { useState } from "react";
import FloatingActionButton from "../../../../components/Ui/Buttons/FAB";
import Card from "../../../../components/Ui/Card/Card";
import Input from "../../../../components/Ui/Fields/Input";
import Layout from "../../../../components/Ui/Layout";
import Modal from "../../../../components/Ui/Modals";

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
    label: "Product A",
    description: "100 stock",
    sub_description: "Rp. 5.000,00 - Rp. 5.200,00",
    image: (
      <Image
        src={"/assets/products/product-image.jpg"}
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
    sub_description: "Rp. 5.000,00 - Rp. 5.200,00",
    image: (
      <Image
        src={"/assets/products/product-image.jpg"}
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
    sub_description: "Rp. 5.000,00 - Rp. 5.200,00",
    image: (
      <Image
        src={"/assets/products/product-image.jpg"}
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

const CashierPage: NextPage = () => {
  const [show, setShow] = useState<ShowActionInterface>({
    delete: false,
    option: false,
    search: false,
    confirm: false,
    stock: false,
  });

  return (
    <Layout title="Cashier" back>
      <div>
        <div>
          <div className="py-3 space-y-8 mb-24">
            {product.map((m, index) => (
              <Card
                key={index}
                label={m.label}
                description={m.description}
                sub_description={m.sub_description}
                image={m.image}
                onClick={() => alert("Added to cart")}
                id={m.id}
              />
            ))}
          </div>
          <FloatingActionButton
            title={
              <div className="w-full flex justify-center items-center gap-x-2">
                <ShoppingCartIcon className="h-7 w-7" />
                <p>25</p>
              </div>
            }
            action="/menu/transaction/cashier/cart"
            dismissable
            options={[
              {
                label: "Search",
                icon: (
                  <Image
                    src={"/assets/icons/Search.svg"}
                    width="30"
                    height="30"
                  />
                ),
                onClick: () => setShow({ search: !show.search }),
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
                  <Image src="/assets/icons/Search-Red.svg" width={30} height={30}/>
                </div>
              }
            />
          </div>
        </Modal>
      </div>
    </Layout>
  );
};

export default CashierPage;
