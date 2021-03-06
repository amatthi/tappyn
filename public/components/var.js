tappyn.factory('tappyn_var', function() {
    var items = {};
    var itemsService = {};

    items.industries = {
        'business': 'Business',
        'entertainment': 'Entertainment',
        'family_relationships': 'Family & Relationships',
        'fitness_wellness': 'Fitness and Wellness',
        'food_drink': 'Food and Drink',
        'hobbies': 'Hobbies',
        'shopping_fashion': 'Shopping and Fashion',
        'sports_outdoors': 'Sports & Outdoors',
        'technology': 'Technology',
        'pets': 'Pets',
        'travel': 'Travel',
        'education': 'Education',
    };
    /*items.interests = [
        { id: '10', text: 'Fashion & Beauty', picture: 'public/img/fashion_interest.png', checked: false },
        { id: '2', text: 'Food & Drink', picture: 'public/img/food_interest.png', checked: false },
        { id: '4', text: 'Health & Fitness', picture: 'public/img/health_interest.png', checked: false },
        { id: '6', text: 'Social & Gaming', picture: 'public/img/social_interest.png', checked: false },
        { id: '3', text: 'Business & Finance', picture: 'public/img/business_interest.png', checked: false },
        { id: '7', text: 'Home & Garden', picture: 'public/img/home_interest.png', checked: false },
        { id: '5', text: 'Travel', picture: 'public/img/travel_interest.png', checked: false },
        { id: '9', text: 'Art & Music', picture: 'public/img/art_interest.png', checked: false },
        { id: '12', text: 'Pets', picture: 'public/img/pets_interest.png', checked: false },
        { id: '13', text: 'Sports & Outdoors', picture: 'public/img/sport_interest.png', checked: false },
        { id: '8', text: 'Education', picture: 'public/img/education_interest.png', checked: false },
        { id: '11', text: 'Tech & Science', picture: 'public/img/tech_interest.png', checked: false }
    ];*/

    items.location_boxes = [
        { id: '1', text: 'Everyone in this location' },
        { id: '2', text: 'People who live in this location' },
        { id: '3', text: 'People recently in this location' },
        { id: '4', text: 'People traveling in this location' },
    ];

    items.additional_info_boxes = {
        '1': 'Specific examples of how you would use our product.',
        '2': 'Specific examples of how the current alternatives to our product fall short.',
        '3': 'Life after you have used our product.',
    };

    items.locations = {
        "All": "All States",
        "AL": "Alabama",
        "AK": "Alaska",
        "AZ": "Arizona",
        "AR": "Arkansas",
        "CA": "California",
        "CO": "Colorado",
        "CT": "Connecticut",
        "DE": "Delaware",
        "DC": "District Of Columbia",
        "FL": "Florida",
        "GA": "Georgia",
        "HI": "Hawaii",
        "ID": "Idaho",
        "IL": "Illinois",
        "IN": "Indiana",
        "IA": "Iowa",
        "KS": "Kansas",
        "KY": "Kentucky",
        "LA": "Louisiana",
        "ME": "Maine",
        "MD": "Maryland",
        "MA": "Massachusetts",
        "MI": "Michigan",
        "MN": "Minnesota",
        "MS": "Mississippi",
        "MO": "Missouri",
        "MT": "Montana",
        "NE": "Nebraska",
        "NV": "Nevada",
        "NH": "New Hampshire",
        "NJ": "New Jersey",
        "NM": "New Mexico",
        "NY": "New York",
        "NC": "North Carolina",
        "ND": "North Dakota",
        "OH": "Ohio",
        "OK": "Oklahoma",
        "OR": "Oregon",
        "PA": "Pennsylvania",
        "RI": "Rhode Island",
        "SC": "South Carolina",
        "SD": "South Dakota",
        "TN": "Tennessee",
        "TX": "Texas",
        "UT": "Utah",
        "VT": "Vermont",
        "VA": "Virginia",
        "WA": "Washington",
        "WV": "West Virginia",
        "WI": "Wisconsin",
        "WY": "Wyoming",
    };

    items.tone_of_voice_boxes = {
        'humour': 'Use Humour',
        'humble': 'Be Humble',
        'scientific': 'Be Scientific',
        'casual': 'Be Casual',
        'inspiring': 'Be Inspiring',
        'adventurous': 'Be Adventurous',
    }

    items.platform_image_settings = {
        'facebook': {
            'aspect_ratio': 1.91 / 1,
            'min_width': 1200,
            'min_height': 628,
        },
        'instagram': {
            'aspect_ratio': 1 / 1,
            'min_width': 540,
            'min_height': 540,
        },
        'twitter': {
            'aspect_ratio': 2 / 1,
            'min_width': 600,
            'min_height': 335,
        }

    };

    items.tooltip_title = {
        'cost_per_result': "Cost Per Click: The amount you're charged each time someone interacts with your ad. Your total charges are based on the amount you spent on the ad divided by all clicks the ad received.",
        'ctr': 'Click Through Rate: The total number of clicks you received (ex: offsite clicks, likes, event responses) divided by the number of impressions.',
        'impressions': "Views: The number of times an ad was viewed. With a few exceptions, an impression is counted each time an ad can be viewed when it enters a person's screen.",
        'results': 'Total Clicks: The total number of clicks on your ad. This may include offsite clicks to your website, Page likes, post comments, event responses or app installs.'
    };

    items.contest_styles = [
        { name: 'Authoritative', img: 'NYT' },
        { name: 'Informed', img: 'eco' },
        { name: 'Instructional', img: 'MH' },
        { name: 'Advice', img: 'Cosmo' },
        { name: 'Viral', img: 'Buzz' },
        { name: 'Catchy', img: 'chub' },
        { name: 'Casual', img: 'People' },
        { name: 'Conversational', img: 'Face' },
        { name: 'Humorous', img: 'DSC' },
    ];

    itemsService.get = function(name) {
        if (items[name]) {
            return items[name];
        }
    };

    itemsService.id_to_obj = function(name, id) {
        if (items[name]) {
            var result = $.grep(items[name], function(e) {
                return e.id == id;
            });
            if (result.length == 1) {
                return result[0];
            } else {
                return {};
            }
        }
    }
    return itemsService;
});
