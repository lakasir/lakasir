import { NextPage } from "next";
import Image from "next/image";
import Link from "next/link";
import { useState } from "react";
import FloatingActionButton from "../../../../../components/Ui/Buttons/FAB";
import Card from "../../../../../components/Ui/Card/Card";
import CardLink from "../../../../../components/Ui/Card/CardLink";
import Layout from "../../../../../components/Ui/Layout";

interface IMenuInterface {
  label: string;
  description: string;
  id: number;
}

const cart: IMenuInterface[] = [
  {
    id: 1,
    label: "3 Items",
    description: "Rp. 5.000,00",
  },
];

interface ShowActionInterface {
  delete?: boolean;
  add?: boolean;
  search?: boolean;
  edit?: boolean;
}

const CartList: NextPage = () => {
  const [show, setShow] = useState<ShowActionInterface>({
    delete: false,
    add: false,
    search: false,
    edit: false,
  });
  return (
    <Layout title="Cart List" back>
      <>
        <div className="py-3 space-y-2 mb-24">
          {cart.map((el, index) => (
            <Link href={`/menu/transaction/cashier/cart/${el.id}`} key={index}>
              <CardLink
                onClick={(e: React.MouseEvent<HTMLAnchorElement>) => {
                  if (show.delete) e.preventDefault();
                }}
              >
                <Card
                  label={el.label}
                  description={el.description}
                  class={{ confirmable: { confirm: "py-2", cancel: "py-2" } }}
                  confirmable={() => {
                    if (show.delete) {
                      alert("confirm deleted");
                    }
                    if (show.edit) {
                      alert("confirm edit");
                    }
                  }}
                  id={el.id}
                  action={
                    <>
                      {show.delete ? (
                        <div
                          className="bg-gray-200 flex justify-center items-center rounded-r-lg w-1/2 ml-auto h-[50px]"
                          id="action-delete"
                        >
                          <Image
                            src={"/assets/icons/Red Delete.svg"}
                            width="30"
                            height="30"
                          />
                        </div>
                      ) : (
                        ""
                      )}
                    </>
                  }
                />
              </CardLink>
            </Link>
          ))}
        </div>
        <FloatingActionButton
          dismissable
          options={[
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
      </>
    </Layout>
  );
};

export default CartList;
