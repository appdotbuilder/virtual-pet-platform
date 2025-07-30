import React, { useState } from 'react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { router } from '@inertiajs/react';
import { Heart, Star, Settings } from 'lucide-react';

interface Pet {
    id: number;
    name: string;
    type: string;
    color: string;
    accessory: string | null;
    attributes: {
        size: string;
        personality: string;
    } | null;
}

interface UserPet {
    id: number;
    custom_name: string;
    custom_color: string;
    custom_accessory: string | null;
    happiness: number;
    hunger: number;
    pet: Pet;
    adopted_at: string;
}

interface Props {
    userPet: UserPet | null;
    availablePets: Pet[];
    isAuthenticated: boolean;
    [key: string]: unknown;
}

export default function VirtualPet({ userPet, availablePets, isAuthenticated }: Props) {
    const [selectedPet, setSelectedPet] = useState<Pet | null>(null);
    const [petName, setPetName] = useState('');
    const [isCustomizing, setIsCustomizing] = useState(false);
    const [customName, setCustomName] = useState(userPet?.custom_name || '');
    const [customColor, setCustomColor] = useState(userPet?.custom_color || '#8B4513');
    const [customAccessory, setCustomAccessory] = useState(userPet?.custom_accessory || '');

    const handleAdoptPet = () => {
        if (!selectedPet || !petName.trim()) return;

        router.post('/adopt-pet', {
            pet_id: selectedPet.id,
            custom_name: petName.trim(),
        }, {
            preserveState: false,
            preserveScroll: true,
        });
    };

    const handleUpdatePet = () => {
        if (!userPet || !customName.trim()) return;

        router.patch(`/user-pets/${userPet.id}`, {
            custom_name: customName.trim(),
            custom_color: customColor,
            custom_accessory: customAccessory || null,
        }, {
            preserveState: false,
            preserveScroll: true,
            onSuccess: () => {
                setIsCustomizing(false);
            },
        });
    };

    const getPetEmoji = (type: string) => {
        const emojis = {
            cat: '🐱',
            dog: '🐶',
            bird: '🐦',
            rabbit: '🐰',
            hamster: '🐹',
            fish: '🐠',
        };
        return emojis[type as keyof typeof emojis] || '🐾';
    };

    const getAccessoryEmoji = (accessory: string | null) => {
        const accessories = {
            hat: '🎩',
            bow: '🎀',
            collar: '⭕',
            bandana: '🔶',
            glasses: '👓',
        };
        return accessory ? accessories[accessory as keyof typeof accessories] || '' : '';
    };

    if (!isAuthenticated) {
        return (
            <AppShell>
                <div className="container mx-auto px-4 py-8">
                    <div className="text-center mb-8">
                        <h1 className="text-4xl font-bold mb-4">🐾 Virtual Pet Platform</h1>
                        <p className="text-xl text-muted-foreground mb-8">
                            Welcome to the magical world of virtual pets! Create an account to adopt and customize your very own digital companion.
                        </p>
                        <div className="flex gap-4 justify-center">
                            <Button onClick={() => router.visit('/register')} size="lg">
                                Create Account
                            </Button>
                            <Button variant="outline" onClick={() => router.visit('/login')} size="lg">
                                Login
                            </Button>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 max-w-4xl mx-auto">
                        {availablePets.slice(0, 4).map((pet) => (
                            <Card key={pet.id} className="text-center">
                                <CardHeader>
                                    <div className="text-6xl mb-2">
                                        {getPetEmoji(pet.type)}
                                        {getAccessoryEmoji(pet.accessory)}
                                    </div>
                                    <CardTitle>{pet.name}</CardTitle>
                                    <CardDescription className="capitalize">{pet.type}</CardDescription>
                                </CardHeader>
                                <CardContent>
                                    <div className="flex justify-center gap-2 mb-2">
                                        <div 
                                            className="w-6 h-6 rounded-full border-2 border-gray-300"
                                            style={{ backgroundColor: pet.color }}
                                        ></div>
                                        <Badge variant="secondary" className="text-xs">
                                            {pet.attributes?.personality}
                                        </Badge>
                                    </div>
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                </div>
            </AppShell>
        );
    }

    if (userPet) {
        return (
            <AppShell>
                <div className="container mx-auto px-4 py-8">
                    <div className="max-w-2xl mx-auto">
                        <div className="text-center mb-8">
                            <h1 className="text-4xl font-bold mb-2">Your Virtual Pet</h1>
                            <p className="text-muted-foreground">Take care of your adorable companion!</p>
                        </div>

                        <Card className="mb-6">
                            <CardHeader className="text-center">
                                <div className="text-8xl mb-4">
                                    <span style={{ color: userPet.custom_color }}>
                                        {getPetEmoji(userPet.pet.type)}
                                    </span>
                                    {getAccessoryEmoji(userPet.custom_accessory)}
                                </div>
                                <CardTitle className="text-2xl">{userPet.custom_name}</CardTitle>
                                <CardDescription className="capitalize">
                                    {userPet.pet.type} • {userPet.pet.attributes?.personality}
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div className="grid grid-cols-2 gap-4 mb-6">
                                    <div className="text-center">
                                        <div className="flex items-center justify-center gap-2 mb-2">
                                            <Heart className="w-5 h-5 text-red-500" />
                                            <span className="font-semibold">Happiness</span>
                                        </div>
                                        <div className="w-full bg-gray-200 rounded-full h-3">
                                            <div 
                                                className="bg-red-500 h-3 rounded-full" 
                                                style={{ width: `${userPet.happiness}%` }}
                                            ></div>
                                        </div>
                                        <span className="text-sm text-muted-foreground">{userPet.happiness}/100</span>
                                    </div>
                                    <div className="text-center">
                                        <div className="flex items-center justify-center gap-2 mb-2">
                                            <Star className="w-5 h-5 text-yellow-500" />
                                            <span className="font-semibold">Hunger</span>
                                        </div>
                                        <div className="w-full bg-gray-200 rounded-full h-3">
                                            <div 
                                                className="bg-yellow-500 h-3 rounded-full" 
                                                style={{ width: `${userPet.hunger}%` }}
                                            ></div>
                                        </div>
                                        <span className="text-sm text-muted-foreground">{userPet.hunger}/100</span>
                                    </div>
                                </div>

                                <div className="text-center">
                                    <Button 
                                        onClick={() => setIsCustomizing(!isCustomizing)}
                                        variant="outline"
                                        className="mb-4"
                                    >
                                        <Settings className="w-4 h-4 mr-2" />
                                        Customize Pet
                                    </Button>
                                </div>

                                {isCustomizing && (
                                    <>
                                        <Separator className="my-4" />
                                        <div className="space-y-4">
                                            <div>
                                                <Label htmlFor="custom_name">Pet Name</Label>
                                                <Input
                                                    id="custom_name"
                                                    value={customName}
                                                    onChange={(e) => setCustomName(e.target.value)}
                                                    placeholder="Enter pet name"
                                                />
                                            </div>
                                            <div>
                                                <Label htmlFor="custom_color">Pet Color</Label>
                                                <div className="flex gap-2 items-center">
                                                    <Input
                                                        id="custom_color"
                                                        type="color"
                                                        value={customColor}
                                                        onChange={(e) => setCustomColor(e.target.value)}
                                                        className="w-16 h-10"
                                                    />
                                                    <Input
                                                        value={customColor}
                                                        onChange={(e) => setCustomColor(e.target.value)}
                                                        placeholder="#8B4513"
                                                        className="flex-1"
                                                    />
                                                </div>
                                            </div>
                                            <div>
                                                <Label htmlFor="custom_accessory">Accessory (optional)</Label>
                                                <select
                                                    id="custom_accessory"
                                                    value={customAccessory}
                                                    onChange={(e) => setCustomAccessory(e.target.value)}
                                                    className="w-full p-2 border border-gray-300 rounded-md"
                                                >
                                                    <option value="">No accessory</option>
                                                    <option value="hat">Hat 🎩</option>
                                                    <option value="bow">Bow 🎀</option>
                                                    <option value="collar">Collar ⭕</option>
                                                    <option value="bandana">Bandana 🔶</option>
                                                    <option value="glasses">Glasses 👓</option>
                                                </select>
                                            </div>
                                            <div className="flex gap-2">
                                                <Button onClick={handleUpdatePet} className="flex-1">
                                                    Save Changes
                                                </Button>
                                                <Button 
                                                    variant="outline" 
                                                    onClick={() => setIsCustomizing(false)}
                                                    className="flex-1"
                                                >
                                                    Cancel
                                                </Button>
                                            </div>
                                        </div>
                                    </>
                                )}
                            </CardContent>
                        </Card>

                        <div className="text-center text-sm text-muted-foreground">
                            Adopted on {new Date(userPet.adopted_at).toLocaleDateString()}
                        </div>
                    </div>
                </div>
            </AppShell>
        );
    }

    // Show pet adoption interface
    return (
        <AppShell>
            <div className="container mx-auto px-4 py-8">
                <div className="text-center mb-8">
                    <h1 className="text-4xl font-bold mb-2">🐾 Adopt Your Virtual Pet</h1>
                    <p className="text-xl text-muted-foreground">
                        Choose your perfect companion from our adorable pets!
                    </p>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    {availablePets.map((pet) => (
                        <Card 
                            key={pet.id} 
                            className={`cursor-pointer transition-all ${
                                selectedPet?.id === pet.id ? 'ring-2 ring-primary' : ''
                            }`}
                            onClick={() => setSelectedPet(pet)}
                        >
                            <CardHeader className="text-center">
                                <div className="text-6xl mb-2">
                                    <span style={{ color: pet.color }}>
                                        {getPetEmoji(pet.type)}
                                    </span>
                                    {getAccessoryEmoji(pet.accessory)}
                                </div>
                                <CardTitle>{pet.name}</CardTitle>
                                <CardDescription className="capitalize">{pet.type}</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-2">
                                    <div className="flex items-center gap-2">
                                        <div 
                                            className="w-4 h-4 rounded-full border"
                                            style={{ backgroundColor: pet.color }}
                                        ></div>
                                        <span className="text-sm">Color</span>
                                    </div>
                                    {pet.attributes && (
                                        <>
                                            <Badge variant="secondary" className="text-xs">
                                                {pet.attributes.personality}
                                            </Badge>
                                            <Badge variant="outline" className="text-xs ml-2">
                                                {pet.attributes.size}
                                            </Badge>
                                        </>
                                    )}
                                </div>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                {selectedPet && (
                    <Card className="max-w-md mx-auto">
                        <CardHeader>
                            <CardTitle>Adopt {selectedPet.name}</CardTitle>
                            <CardDescription>Give your new pet a custom name!</CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div>
                                <Label htmlFor="pet_name">Pet Name</Label>
                                <Input
                                    id="pet_name"
                                    value={petName}
                                    onChange={(e) => setPetName(e.target.value)}
                                    placeholder="Enter a name for your pet"
                                />
                            </div>
                            <Button 
                                onClick={handleAdoptPet} 
                                className="w-full"
                                disabled={!petName.trim()}
                            >
                                Adopt {selectedPet.name}
                            </Button>
                        </CardContent>
                    </Card>
                )}
            </div>
        </AppShell>
    );
}