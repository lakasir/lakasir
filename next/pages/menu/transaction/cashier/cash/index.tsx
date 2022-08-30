import { UserAddIcon } from "@heroicons/react/outline";
import { BackspaceIcon, PrinterIcon } from "@heroicons/react/solid";
import { NextPage } from "next";
import Image from "next/image";
import { MouseEventHandler, useState } from "react";
import FloatingActionButton from "../../../../../components/Ui/Buttons/FAB";
import Layout from "../../../../../components/Ui/Layout";
import Modal from "../../../../../components/Ui/Modals";

interface INumInterface {
  title: number | string | JSX.Element;
  onClick?: MouseEventHandler<HTMLDivElement> | undefined;
  act?: string;
}

const num: INumInterface[] = [
  { title: 1 },
  { title: 2 },
  { title: 3 },
  { title: 4 },
  { title: 5 },
  { title: 6 },
  { title: 7 },
  { title: 8 },
  { title: 9 },
  { title: <BackspaceIcon className="h-8 w-8" />, act: "backspace" },
  { title: 0 },
  {
    title: (
      <Image src="/assets/icons/Close Keyboard.svg" height={32} width={32} />
    ),
    act: "shortcut",
  },
];

const CashPayment: NextPage = () => {
  const [cashTotal, setCashTotal] = useState(0);
  const [openModal, setOpenModal] = useState(false);
  let strNumber = "";
  return (
    <Layout
      title={<span className="font-normal text-lg">Total Rp. 100.000,00</span>}
      back
    >
      <div>
        <div className="space-y-16">
          <div className="py-5 text-center">
            <p className="text-5xl font-bold">
              {cashTotal.toLocaleString("id-ID", {
                style: "currency",
                currency: "IDR",
                maximumFractionDigits: 0,
              })}
            </p>
          </div>
          <div className="grid grid-cols-3 gap-5">
            {num.map((n, index) => (
              <div
                className="h-28 w-28 rounded-xl bg-gray-300 text-5xl flex justify-center items-center cursor-pointer"
                onClick={(e) => {
                  if (n.act != undefined) {
                    switch (n.act) {
                      case "shortcut":
                        break;
                      case "backspace":
                        if (isNaN(cashTotal) || cashTotal == 0 || cashTotal.toString().length == 1) {
                          setCashTotal(0);
                        } else {
                          let deleted = cashTotal
                            .toString()
                            .slice(0, cashTotal.toString().length - 1);
                          setCashTotal(parseInt(deleted));
                        }
                        break;
                    }
                  } else {
                    strNumber += n.title.toString();
                    setCashTotal(parseInt(cashTotal + strNumber));
                  }
                }}
                key={index}
              >
                <p>{n.title}</p>
              </div>
            ))}
          </div>
        </div>
        <FloatingActionButton
          title="Pay it"
          action="/"
          dismissable
          options={[
            {
              label: "Add Member",
              icon: <UserAddIcon className="h-8 w-8" />,
              onClick: () => setOpenModal(true),
            },
            {
              label: "Print",
              icon: <PrinterIcon className="h-8 w-8" />,
              onClick: () => console.log("OK"),
            },
          ]}
        />
        <Modal onClose={setOpenModal} open={openModal}>
        <div className="w-11/12 mx-auto bg-white pb-7"></div>
        </Modal>
      </div>
    </Layout>
  );
};

export default CashPayment;
