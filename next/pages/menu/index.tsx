import { Card } from "@/ui/Card";
import { Layout } from "@/ui/Layout";
import { ShoppingCartIcon, ChartPieIcon, UsersIcon, UserIcon, InformationCircleIcon, CogIcon } from "@heroicons/react/solid";
import { NextPage } from "next";
import Link from "next/link";

interface IMenuInterface {
  label: string;
  description: string;
  href: string;
  icon: JSX.Element;
}

const menu: IMenuInterface[] = [
  {
    label: "Transaction",
    description: "Sell your product or service",
    icon: <ShoppingCartIcon className="h-6 w-6 my-3 text-white" />,
    href: "/menu/transaction",
  },
  {
    label: "Product",
    description: "Show list your product.",
    icon: <ChartPieIcon className="h-6 w-6 my-3 text-white" />,
    href: "/menu/product",
  },
  {
    label: "Member",
    description: "Member is a success key for your  bussiness",
    icon: <UsersIcon className="h-6 w-6 my-3 text-white" />,
    href: "/menu/member",
  },
  {
    label: "Profile",
    description: "Every person is has an iconic profile",
    icon: <UserIcon className="h-6 w-6 my-3 text-white" />,
    href: "/menu/profile",
  },
  {
    label: "About",
    description: "Every bussiness is has an iconic purpose",
    icon: <InformationCircleIcon className="h-6 w-6 my-3 text-white" />,
    href: "/menu/about",
  },
  {
    label: "Setting",
    description: "Lakasir is configurable",
    icon: <CogIcon className="h-6 w-6 my-3 text-white" />,
    href: "/menu/setting",
  },
];

const Menu: NextPage = () => {
  return (
    <Layout title="Menu">
      <div className="py-3 space-y-4">
        {menu.map((m, index) => (
          <Link href={m.href} key={index}>
              <Card
                label={m.label}
                description={m.description}
                image={m.icon}
                confirmable={() => alert("CONFIRMED")}
                id={index}
              />
          </Link>
        ))}
      </div>
    </Layout>
  );
};

export default Menu;
