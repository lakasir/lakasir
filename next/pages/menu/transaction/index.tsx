import { Layout } from "@/ui/Layout";
import { NextPage } from "next";
import Link from "next/link";

interface IMenuInterface {
  label: string;
  description: string;
  href: string;
  icon: string | JSX.Element;
}

const menu: IMenuInterface[] = [
  {
    label: "Cashier",
    description: "",
    icon: <img src={"./../assets/icons/Unpacking.svg"} width="50" height="50"/>,
    href: "/menu/transaction/cashier",
  },
  {
    label: "Service",
    description: "",
    icon: <img src={"./../assets/icons/Service.svg"} width="50" height="50"/>,
    href: "/menu/transaction/service",
  },
];
const Transaction: NextPage = () => {
  return (
    <Layout title="Transaction" back={true}>
      <div className="py-3 space-y-8">
        {menu.map((m, index) => (
          <Link href={m.href} key={index}>
            <a className="block">
              <div className="w-full h-24 rounded-lg overflow-hidden cursor-pointer">
                <div className="bg-lakasir-primary rounded-lg w-[91px] h-[93px] absolute flex items-center justify-center">
                  {m.icon}
                </div>
                <div className="flex">
                  <div className="bg-lakasir-primary rounded-lg w-[91px] h-[93px]"></div>
                  <div className="w-3/4 flex">
                    <div className="items-center ml-7 mt-7">
                      <p className="text-xl">{m.label}</p>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          </Link>
        ))}
      </div>
    </Layout>
  );
};

export default Transaction;
