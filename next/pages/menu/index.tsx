import { NextPage } from "next";
import Link from "next/link";
import Layout from "../../components/Ui/Layout";

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
    icon: "",
    href: "/menu/transaction",
  },
  {
    label: "Product",
    description: "Show list your product.",
    icon: "",
    href: "/menu/product",
  },
  {
    label: "Member",
    description: "Member is a success key for your  bussiness",
    icon: "",
    href: "/menu/member",
  },
  {
    label: "Profile",
    description: "Every person is has an iconic profile",
    icon: "",
    href: "/menu/profile",
  },
  {
    label: "About",
    description: "Every bussiness is has an iconic purpose",
    icon: "",
    href: "/menu/about",
  },
  {
    label: "Setting",
    description: "Lakasir is configurable",
    icon: "",
    href: "/menu/setting",
  },
];

const Menu: NextPage = () => {
  return (
    <Layout title="Menu">
      <div className="py-9 space-y-8">
        {menu.map((m) => (
          <Link href={m.href}>
            <a className="block">
              <div className="w-full h-24 rounded-lg overflow-hidden cursor-pointer">
                <div className="bg-[#E0DCFD] rounded-lg w-1/4 h-24 absolute flex items-center justify-center">
                  {/* <CashIcon className="text-gray-500 h-16 w-16" /> */}
                </div>
                <div className="flex">
                  <div className="bg-[#E0DCFD] rounded-lg w-1/4 h-24"></div>
                  <div className="bg-[#F1EFFF] w-3/4 flex">
                    <div className="items-center ml-5 mt-5">
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
