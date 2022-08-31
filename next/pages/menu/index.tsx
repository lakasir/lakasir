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
    label: "Transaction",
    description: "Sell your product or service",
    icon: <img src={"./assets/icons/Cash.svg"} width="50" height="50"/>,
    href: "/menu/transaction",
  },
  {
    label: "Product",
    description: "Show list your product.",
    icon: <img src="./assets/icons/Shipping Product.svg" width="50" height="50"/>,
    href: "/menu/product",
  },
  {
    label: "Member",
    description: "Member is a success key for your  bussiness",
    icon: <img src="./assets/icons/Leader.svg" width="50" height="50"/>,
    href: "/menu/member",
  },
  {
    label: "Profile",
    description: "Every person is has an iconic profile",
    icon: <img src="./assets/icons/User.svg" width="50" height="50"/>,
    href: "/menu/profile",
  },
  {
    label: "About",
    description: "Every bussiness is has an iconic purpose",
    icon: <img src="./assets/icons/Info.svg" width="50" height="50"/>,
    href: "/menu/about",
  },
  {
    label: "Setting",
    description: "Lakasir is configurable",
    icon: <img src="./assets/icons/Settings.svg" width="50" height="50"/>,
    href: "/menu/setting",
  },
];

const Menu: NextPage = () => {
  return (
    <Layout title="Menu">
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
                    <div className="items-center ml-7 mt-5">
                      <p className="text-xl">{m.label}</p>
                      <p className="font-light text-sm">{m.description}</p>
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

export default Menu;
