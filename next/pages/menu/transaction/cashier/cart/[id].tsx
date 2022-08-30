import { CheckIcon, FolderAddIcon } from "@heroicons/react/outline";
import { NextPage } from "next";
import Image from "next/image";
import { useRouter } from "next/router";
import { useState } from "react";
import FloatingActionButton from "../../../../../components/Ui/Buttons/FAB";
import Card from "../../../../../components/Ui/Card/Card";
import Layout from "../../../../../components/Ui/Layout";

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

const CartDetail: NextPage = () => {
  const [show, setShow] = useState<ShowActionInterface>({
    delete: false,
    option: false,
    search: false,
    confirm: false,
    stock: false,
  });

  const router = useRouter();

  return (
    <Layout title="Cashier Cart" back>
      <div>
        <div>
          <div className="py-3 space-y-8 mb-24">
            {product.map((m, index) => (
              <Card
                key={index}
                label={m.label}
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
                      <Image
                        src={"/assets/icons/Red Delete.svg"}
                        width="30"
                        height="30"
                      />
                    </div>
                  ) : (
                    <></>
                  )
                }
                id={m.id}
              />
            ))}
          </div>
          <FloatingActionButton
            title={
              <div className="w-full flex justify-center items-center -mt-2">
                <CheckIcon className="h-10 w-10" />
              </div>
            }
            action="/menu/transaction/cashier/cash"
            dismissable
            options={[
              {
                label: "New Session",
                icon: <FolderAddIcon className="h-8 w-8" />,
                onClick: () => router.push("/menu/transaction/cashier"),
              },
              {
                label: "Delete",
                icon: (
                  <Image
                    src={"/assets/icons/Delete.svg"}
                    width="30"
                    height="30"
                  />
                ),
                onClick: () => setShow({ delete: !show.delete }),
              },
            ]}
          />
        </div>
      </div>
    </Layout>
  );
};
export default CartDetail;
